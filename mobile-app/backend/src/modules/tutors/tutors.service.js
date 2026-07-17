import { pool, query, queryOne } from '../../config/db.js';
import { errors } from '../../utils/http.js';
import { mediaUrl, safeJsonParse } from '../../utils/media.js';
import { nowSql } from '../../utils/time.js';
import { sendMail } from '../../utils/mailer.js';

// A tutor is publicly listable only when verified, active, approved, NOT a
// university tutor, and covered by an ACTIVE subscription within its period.
// This mirrors TutorModel::searchTutors so the app and website agree.
const ELIGIBLE_JOINS = `
  FROM users
  JOIN tutor_subscriptions ts
    ON ts.user_id = users.id
   AND ts.status = 'active'
   AND ts.current_period_start <= NOW()
   AND ts.current_period_end >= NOW()
  LEFT JOIN subscription_plans sp ON sp.id = ts.plan_id
  LEFT JOIN university_college_tutors uct
    ON uct.user_id = users.id OR uct.email = users.email OR uct.username = users.username
  WHERE users.role = 'trainer'
    AND users.tutor_status = 'approved'
    AND users.is_verified = 1
    AND users.is_active = 1
    AND users.deleted_at IS NULL
    AND uct.id IS NULL
`;

function buildFilters(filters) {
  const where = [];
  const params = {};

  if (filters.q) {
    where.push(`CONCAT(users.first_name, ' ', users.last_name) LIKE :q`);
    params.q = `%${filters.q}%`;
  }
  if (filters.district) {
    where.push(`users.district = :district`);
    params.district = filters.district;
  }
  if (filters.teaching_mode) {
    where.push(`users.teaching_mode = :teaching_mode`);
    params.teaching_mode = filters.teaching_mode;
  }
  // curriculum / level / subject live nested in the structured_subjects JSON:
  //   { "MANEB": { "levels": { "Secondary: Forms 1–4": ["Mathematics", ...] } } }
  //
  // These used to be three independent LIKE scans of the raw text, which ignored
  // the nesting: a tutor teaching MANEB English and Cambridge Mathematics matched
  // curriculum=MANEB AND subject=Mathematics, even though they teach no such
  // thing. Walk the actual JSON path instead. (MySQL 8 — JSON path can be bound,
  // so the user-supplied names are still parameterised, not interpolated.)
  const jsonPath = (...segments) =>
    '$' + segments.map((s) => `."${String(s).replace(/"/g, '\\"')}"`).join('');

  if (filters.curriculum && filters.level && filters.subject) {
    where.push(`JSON_CONTAINS(JSON_EXTRACT(users.structured_subjects, :subjPath), :subject)`);
    params.subjPath = jsonPath(filters.curriculum, 'levels', filters.level);
    params.subject = JSON.stringify(filters.subject);
  } else if (filters.curriculum && filters.level) {
    where.push(`JSON_CONTAINS_PATH(users.structured_subjects, 'one', :lvlPath)`);
    params.lvlPath = jsonPath(filters.curriculum, 'levels', filters.level);
  } else if (filters.curriculum && filters.subject) {
    // Any level within this curriculum.
    where.push(
      `JSON_SEARCH(JSON_EXTRACT(users.structured_subjects, :curLevelsPath), 'one', :subjectStr) IS NOT NULL`
    );
    params.curLevelsPath = jsonPath(filters.curriculum, 'levels');
    params.subjectStr = filters.subject;
  } else if (filters.curriculum) {
    where.push(`JSON_CONTAINS_PATH(users.structured_subjects, 'one', :curPath)`);
    params.curPath = jsonPath(filters.curriculum);
  } else {
    // No curriculum to anchor to: fall back to a plain containment check. The
    // app's filter sheet won't produce this, but the API stays usable.
    if (filters.level) {
      where.push(`users.structured_subjects LIKE :level`);
      params.level = `%"${filters.level}"%`;
    }
    if (filters.subject) {
      where.push(`users.structured_subjects LIKE :subject`);
      params.subject = `%"${filters.subject}"%`;
    }
  }

  return { clause: where.length ? ` AND ${where.join(' AND ')}` : '', params };
}

function orderClause(sort) {
  switch (sort) {
    case 'experience':
      return `ORDER BY users.experience_years DESC, users.rating DESC`;
    case 'reviews':
      return `ORDER BY users.review_count DESC, users.rating DESC`;
    case 'rating':
    default:
      // Subscription-aware ranking, then rating/reviews (improves on the site).
      return `ORDER BY FIELD(sp.search_ranking,'top','priority','normal','low'),
                       users.featured DESC, users.rating DESC, users.review_count DESC`;
  }
}

export function presentTutorCard(u) {
  const structured = safeJsonParse(u.structured_subjects, {});
  const curricula = Object.keys(structured || {});
  const subjects = new Set();
  for (const c of Object.values(structured || {})) {
    for (const subs of Object.values(c?.levels || {})) {
      (Array.isArray(subs) ? subs : []).forEach((s) => subjects.add(s));
    }
  }
  return {
    id: u.id,
    name: `${u.first_name ?? ''} ${u.last_name ?? ''}`.trim(),
    username: u.username,
    profilePicture: mediaUrl(u.profile_picture),
    district: u.district,
    location: u.location,
    teachingMode: u.teaching_mode,
    experienceYears: u.experience_years,
    schoolName: u.school_name,
    rating: Number(u.rating ?? 0),
    reviewCount: Number(u.review_count ?? 0),
    featured: !!u.featured,
    planName: u.plan_name ?? null,
    badgeLevel: u.badge_level ?? 'none',
    curricula,
    subjects: [...subjects],
  };
}

export async function searchTutors(filters, { page, limit }) {
  const { clause, params } = buildFilters(filters);
  const offset = (page - 1) * limit;

  const rows = await query(
    `SELECT users.*, sp.name AS plan_name, sp.badge_level, sp.search_ranking
     ${ELIGIBLE_JOINS} ${clause}
     GROUP BY users.id
     ${orderClause(filters.sort)}
     LIMIT :limit OFFSET :offset`,
    { ...params, limit, offset }
  );

  const countRow = await queryOne(
    `SELECT COUNT(DISTINCT users.id) AS total ${ELIGIBLE_JOINS} ${clause}`,
    params
  );

  return {
    items: rows.map(presentTutorCard),
    total: Number(countRow?.total ?? 0),
  };
}

export async function getTutorByIdOrUsername(identifier) {
  const byId = /^\d+$/.test(String(identifier));
  const row = await queryOne(
    `SELECT users.*, sp.name AS plan_name, sp.badge_level, sp.show_whatsapp
     ${ELIGIBLE_JOINS} AND users.${byId ? 'id' : 'username'} = :identifier
     GROUP BY users.id LIMIT 1`,
    { identifier }
  );
  if (!row) throw errors.notFound('Tutor not found');

  const card = presentTutorCard(row);
  return {
    ...card,
    bio: row.bio,
    bioVideo: mediaUrl(row.bio_video),
    coverPhoto: mediaUrl(row.cover_photo),
    bestCallTime: row.best_call_time,
    preferredContactMethod: row.preferred_contact_method,
    whatsappNumber: row.show_whatsapp ? row.whatsapp_number : null,
    phone: row.phone_visible ? row.phone : null,
    email: row.email_visible ? row.email : null,
    structuredSubjects: safeJsonParse(row.structured_subjects, {}),
    availability: safeJsonParse(row.availability, {}),
  };
}

export async function getTutorReviews(tutorId, { page, limit }) {
  const offset = (page - 1) * limit;
  const items = await query(
    `SELECT id, reviewer_name, rating, comment, is_anonymous, created_at
     FROM reviews WHERE tutor_id = :id
     ORDER BY created_at DESC LIMIT :limit OFFSET :offset`,
    { id: tutorId, limit, offset }
  );
  const countRow = await queryOne(`SELECT COUNT(*) AS total FROM reviews WHERE tutor_id = :id`, {
    id: tutorId,
  });
  return {
    items: items.map((r) => ({
      id: r.id,
      reviewerName: r.is_anonymous ? 'Anonymous' : r.reviewer_name,
      rating: Number(r.rating),
      comment: r.comment,
      createdAt: r.created_at,
    })),
    total: Number(countRow?.total ?? 0),
  };
}

/**
 * Create a PUBLIC review (no account needed — reviewer_name comes from the form,
 * exactly like the website) and recompute the tutor's aggregate rating/count.
 */
export async function createReview(tutorId, { reviewerName, rating, comment, isAnonymous }) {
  const tutor = await queryOne(
    `SELECT id FROM users WHERE id = :id AND role='trainer' AND deleted_at IS NULL`,
    { id: tutorId }
  );
  if (!tutor) throw errors.notFound('Tutor not found');

  const now = nowSql();

  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();
    const [ins] = await conn.execute(
      `INSERT INTO reviews (tutor_id, reviewer_name, rating, comment, is_anonymous, created_at)
       VALUES (:tutorId, :reviewerName, :rating, :comment, :isAnonymous, :now)`,
      {
        tutorId,
        reviewerName,
        rating,
        comment: comment ?? null,
        isAnonymous: isAnonymous ? 1 : 0,
        now,
      }
    );
    await conn.execute(
      `UPDATE users u
         JOIN (SELECT ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS cnt
                 FROM reviews WHERE tutor_id = :tutorId) agg
         SET u.rating = agg.avg_rating, u.review_count = agg.cnt, u.updated_at = :now
       WHERE u.id = :tutorId`,
      { tutorId, now }
    );
    await conn.commit();
    return { id: ins.insertId, rating, comment: comment ?? null, reviewerName, createdAt: now };
  } catch (e) {
    await conn.rollback();
    throw e;
  } finally {
    conn.release();
  }
}

/**
 * Record a usage metric keyed by the visitor's IP, deduped to one row per IP
 * per metric per billing period — so analytics can COUNT(DISTINCT visitor_ip)
 * and get real unique visitors (exactly how the website counts). Fire-and-forget:
 * a tracking failure must never break the page the user asked for.
 */
async function recordUsage(tutorId, metricType, visitorIp, extraMeta = {}) {
  const ip = String(visitorIp || 'unknown');
  const now = nowSql();
  const periodStart = now.slice(0, 8) + '01';
  const d = new Date(now);
  const periodEnd = new Date(d.getFullYear(), d.getMonth() + 1, 0).toISOString().slice(0, 10);
  try {
    const existing = await queryOne(
      `SELECT id FROM usage_tracking
        WHERE user_id = :uid AND metric_type = :type
          AND period_start = :ps AND period_end = :pe
          AND JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.visitor_ip')) = :ip
        LIMIT 1`,
      { uid: tutorId, type: metricType, ps: periodStart, pe: periodEnd, ip }
    );
    if (existing) return; // this IP already counted for this metric this period
    await query(
      `INSERT INTO usage_tracking (user_id, metric_type, metric_value, metadata, tracked_at, period_start, period_end)
       VALUES (:uid, :type, 1, :meta, :now, :ps, :pe)`,
      {
        uid: tutorId,
        type: metricType,
        meta: JSON.stringify({ source: 'mobile_app', visitor_ip: ip, ...extraMeta }),
        now,
        ps: periodStart,
        pe: periodEnd,
      }
    );
  } catch (e) {
    console.error(`[usage] ${metricType} track failed:`, e.message);
  }
}

/** Record a unique profile view (deduped by IP for the billing period). */
export async function recordProfileView(tutorId, visitorIp) {
  await recordUsage(tutorId, 'profile_views', visitorIp);
}

/**
 * Record a contact click when a visitor taps WhatsApp / Call / Email on the
 * profile (the website's Home::trackContactClick). Deduped by IP per period so
 * the analytics "Contact clicks" reflects unique people, not repeat taps.
 */
export async function trackContactClick(tutorId, method, visitorIp) {
  const tutor = await queryOne(
    `SELECT id FROM users WHERE id = :id AND role='trainer' AND deleted_at IS NULL`,
    { id: tutorId }
  );
  if (!tutor) throw errors.notFound('Tutor not found');
  await recordUsage(tutorId, 'contact_clicks', visitorIp, { method: String(method || 'unknown') });
  return { ok: true };
}

/**
 * Public "contact tutor" — emails the tutor (best-effort) and records a
 * contact_click in usage_tracking, mirroring the website's Home::sendMessage +
 * trackContactClick. No account required for the sender.
 */
export async function contactTutor(tutorId, input, visitorIp) {
  const tutor = await queryOne(
    `SELECT id, email, first_name, last_name FROM users
     WHERE id = :id AND role='trainer' AND deleted_at IS NULL`,
    { id: tutorId }
  );
  if (!tutor) throw errors.notFound('Tutor not found');

  const tutorName = `${tutor.first_name ?? ''} ${tutor.last_name ?? ''}`.trim();
  const phoneLine = input.senderPhone ? `<p><strong>Phone:</strong> ${input.senderPhone}</p>` : '';
  const html = `
    <h2>New message via TutorConnect Malawi</h2>
    <p>Hi ${tutorName}, you have a new enquiry from a prospective student/parent.</p>
    <p><strong>From:</strong> ${input.senderName} (${input.senderEmail})</p>
    ${phoneLine}
    ${input.contactPreference ? `<p><strong>Preferred contact:</strong> ${input.contactPreference} — ${input.contactDetail ?? ''}</p>` : ''}
    <p><strong>Subject:</strong> ${input.subject}</p>
    <p><strong>Message:</strong></p>
    <p>${String(input.message).replace(/\n/g, '<br>')}</p>`;

  const mail = await sendMail({
    to: tutor.email,
    subject: `TutorConnect Malawi: ${input.subject}`,
    html,
    replyTo: input.senderEmail,
  });

  // Persist the enquiry so the tutor sees it IN THE APP even if the email fails.
  // This is the source of truth; the email is just a heads-up. Storing it before
  // returning means no enquiry is ever lost to an SMTP hiccup.
  await query(
    `INSERT INTO tutor_enquiries
       (tutor_id, sender_name, sender_email, sender_phone, subject, message,
        contact_preference, email_sent, is_read, source, created_at)
     VALUES (:tid, :name, :email, :phone, :subject, :message, :pref, :sent, 0, 'mobile_app', :now)`,
    {
      tid: tutorId,
      name: input.senderName,
      email: input.senderEmail,
      phone: input.senderPhone || null,
      subject: input.subject,
      message: input.message,
      pref: input.contactPreference || null,
      sent: mail.sent ? 1 : 0,
      now: nowSql(),
    }
  ).catch((e) => console.error('[enquiry] save failed:', e.message));

  // Record the enquiry as a contact (unique by IP) for subscription analytics.
  await recordUsage(tutorId, 'contact_clicks', visitorIp, { sender: input.senderEmail, via: 'message' });

  // The enquiry is safely stored, so this always succeeds for the sender. We
  // still report whether the email heads-up reached the tutor.
  return {
    sent: mail.sent,
    message: mail.sent
      ? 'Your message has been sent to the tutor.'
      : 'Your message was saved and the tutor will see it in their app.',
  };
}
