import { query, queryOne } from '../../config/db.js';
import { errors } from '../../utils/http.js';
import { nowSql } from '../../utils/time.js';
import { mediaUrl, safeJsonParse } from '../../utils/media.js';
import { verifyPassword, hashPassword } from '../../utils/password.js';
import { issueTokens } from '../auth/tokens.service.js';

// Columns a tutor may edit about themselves (everything else is admin-managed).
const EDITABLE = {
  firstName: 'first_name',
  lastName: 'last_name',
  phone: 'phone',
  gender: 'gender',
  district: 'district',
  location: 'location',
  experienceYears: 'experience_years',
  teachingMode: 'teaching_mode',
  bio: 'bio',
  whatsappNumber: 'whatsapp_number',
  phoneVisible: 'phone_visible',
  emailVisible: 'email_visible',
  bestCallTime: 'best_call_time',
  preferredContactMethod: 'preferred_contact_method',
  isEmployed: 'is_employed',
  schoolName: 'school_name',
};

// What a tutor must supply before an admin can review them. Kept on the server
// so the app and the admin agree on what "everything is submitted" means.
const REQUIRED_DOCS = ['national_id', 'academic_certificates', 'teaching_qualification', 'police_clearance'];

function buildCompletion(u) {
  const subjects = safeJsonParse(u.structured_subjects, {});
  const availability = safeJsonParse(u.availability, {});
  const docs = safeJsonParse(u.verification_documents, []);
  const docTypes = new Set(
    (Array.isArray(docs) ? docs : []).map((d) => String(d?.document_type || '').toLowerCase())
  );

  const hasSubjects = Object.values(subjects || {}).some((c) =>
    Object.values(c?.levels || {}).some((list) => Array.isArray(list) && list.length > 0)
  );

  const items = [
    { key: 'photo', label: 'Profile photo', done: !!u.profile_picture },
    { key: 'bio', label: 'Bio (40+ characters)', done: String(u.bio || '').trim().length >= 40 },
    { key: 'location', label: 'District & location', done: !!u.district && !!u.location },
    { key: 'contact', label: 'Phone number', done: !!u.phone },
    // NOTE: experience_years and teaching_mode both have DB defaults, so they
    // can never read as "missing" — they'd inflate the bar for free. WhatsApp is
    // genuinely unset on a new account and is how students reach a tutor.
    { key: 'whatsapp', label: 'WhatsApp number', done: !!u.whatsapp_number },
    { key: 'subjects', label: 'Subjects you teach', done: hasSubjects },
    {
      key: 'availability',
      label: 'Availability',
      done: Array.isArray(availability?.days) && availability.days.length > 0,
    },
    ...REQUIRED_DOCS.map((type) => ({
      key: `doc_${type}`,
      label: type.replace(/_/g, ' ').replace(/\b\w/g, (m) => m.toUpperCase()),
      done: docTypes.has(type),
      isDocument: true,
    })),
  ];

  const doneCount = items.filter((i) => i.done).length;
  return {
    items,
    percent: Math.round((doneCount / items.length) * 100),
    doneCount,
    totalCount: items.length,
    allSubmitted: doneCount === items.length,
  };
}

export async function getMyProfile(userId) {
  const u = await queryOne(`SELECT * FROM users WHERE id = :id AND deleted_at IS NULL`, { id: userId });
  if (!u) throw errors.notFound('User not found');
  return {
    completion: buildCompletion(u),
    id: u.id,
    username: u.username,
    email: u.email,
    role: u.role,
    firstName: u.first_name,
    lastName: u.last_name,
    gender: u.gender,
    phone: u.phone,
    district: u.district,
    location: u.location,
    experienceYears: u.experience_years,
    teachingMode: u.teaching_mode,
    bio: u.bio,
    bioVideo: mediaUrl(u.bio_video),
    profilePicture: mediaUrl(u.profile_picture),
    coverPhoto: mediaUrl(u.cover_photo),
    // Which verification documents are already on file (by type), so the app can
    // show an "uploaded" tick next to each.
    uploadedDocuments: [
      ...new Set(
        (safeJsonParse(u.verification_documents, []) || [])
          .map((d) => String(d?.document_type || '').toLowerCase())
          .filter(Boolean)
      ),
    ],
    whatsappNumber: u.whatsapp_number,
    phoneVisible: !!u.phone_visible,
    emailVisible: !!u.email_visible,
    bestCallTime: u.best_call_time,
    preferredContactMethod: u.preferred_contact_method,
    isEmployed: !!u.is_employed,
    schoolName: u.school_name,
    tutorStatus: u.tutor_status,
    // Document resubmission (admin asked the tutor to re-upload some documents).
    needsResubmission: !!u.needs_resubmission,
    resubmissionMessage: u.resubmission_message ?? null,
    resubmissionDocs: (safeJsonParse(u.resubmission_special_docs, []) || []).filter(
      (d) => d && d.needs_resubmission
    ),
    isVerified: !!u.is_verified,
    registrationCompleted: !!u.registration_completed,
    subscriptionPlan: u.subscription_plan,
    subscriptionExpiresAt: u.subscription_expires_at,
    rating: Number(u.rating ?? 0),
    reviewCount: Number(u.review_count ?? 0),
    structuredSubjects: safeJsonParse(u.structured_subjects, {}),
    availability: safeJsonParse(u.availability, {}),
  };
}

export async function updateMyProfile(userId, input) {
  const sets = [];
  const params = { id: userId, now: nowSql() };
  for (const [key, column] of Object.entries(EDITABLE)) {
    if (input[key] !== undefined) {
      sets.push(`${column} = :${key}`);
      params[key] = typeof input[key] === 'boolean' ? (input[key] ? 1 : 0) : input[key];
    }
  }
  if (sets.length === 0) throw errors.badRequest('No editable fields provided');

  sets.push('updated_at = :now');
  await query(`UPDATE users SET ${sets.join(', ')} WHERE id = :id`, params);
  return getMyProfile(userId);
}

export async function updateAvailability(userId, availability) {
  await query(`UPDATE users SET availability = :json, updated_at = :now WHERE id = :id`, {
    json: JSON.stringify(availability),
    now: nowSql(),
    id: userId,
  });
  return { availability };
}

/**
 * The subject cap the tutor is currently bound by. Uses the ACTIVE plan; if the
 * tutor has no active subscription, falls back to the free trainer plan's limit
 * so an un-subscribed tutor still gets the free-tier cap rather than unlimited.
 * Returns { max, planName } where max === 0 means unlimited (Premium).
 */
async function getEffectiveMaxSubjects(userId) {
  const active = await queryOne(
    `SELECT sp.max_subjects AS max, sp.name AS plan
       FROM tutor_subscriptions ts
       JOIN subscription_plans sp ON sp.id = ts.plan_id
      WHERE ts.user_id = :id AND ts.status = 'active'
        AND ts.current_period_start <= NOW() AND ts.current_period_end >= NOW()
      ORDER BY ts.current_period_end DESC LIMIT 1`,
    { id: userId }
  );
  if (active) return { max: Number(active.max) || 0, planName: active.plan };

  const free = await queryOne(
    `SELECT max_subjects AS max, name AS plan FROM subscription_plans
      WHERE portal_type = 'trainer' AND price_monthly = 0 AND is_active = 1
      ORDER BY sort_order ASC LIMIT 1`
  );
  return { max: Number(free?.max) || 0, planName: free?.plan ?? 'Free' };
}

/** Total subjects selected across every curriculum/level. */
function countSubjects(structured) {
  let n = 0;
  for (const c of Object.values(structured || {})) {
    for (const list of Object.values(c?.levels || {})) {
      if (Array.isArray(list)) n += list.length;
    }
  }
  return n;
}

export async function updateStructuredSubjects(userId, structuredSubjects) {
  // Enforce the plan's subject limit server-side (0 = unlimited). The app also
  // guards this in the UI, but the cap must hold even against a direct API call.
  const { max, planName } = await getEffectiveMaxSubjects(userId);
  const count = countSubjects(structuredSubjects);
  if (max > 0 && count > max) {
    throw errors.badRequest(
      `Your ${planName} plan allows up to ${max} subject${max === 1 ? '' : 's'}. ` +
        `You selected ${count}. Remove ${count - max} or upgrade your plan.`
    );
  }

  await query(`UPDATE users SET structured_subjects = :json, updated_at = :now WHERE id = :id`, {
    json: JSON.stringify(structuredSubjects),
    now: nowSql(),
    id: userId,
  });
  return { structuredSubjects, maxSubjects: max, subjectCount: count };
}

// Update a single image column (profile_picture / cover_photo) with a relative
// path like "uploads/profile_photos/profile_92_123.jpg" — same convention the
// PHP website uses, so both apps read the image.
export async function setUserImage(userId, column, relPath) {
  const allowed = { profile_picture: 'profile_picture', cover_photo: 'cover_photo' };
  const col = allowed[column];
  if (!col) throw errors.badRequest('Invalid image field');
  await query(`UPDATE users SET ${col} = :path, updated_at = :now WHERE id = :id`, {
    path: relPath,
    now: nowSql(),
    id: userId,
  });
  return getMyProfile(userId);
}

// Append a verification document to the users.verification_documents JSON array,
// matching the exact shape the website writes/reads.
export async function addVerificationDocument(userId, { documentType, filePath, originalFilename }) {
  const u = await queryOne(`SELECT verification_documents FROM users WHERE id = :id`, { id: userId });
  const parsed = safeJsonParse(u?.verification_documents, []);
  const list = Array.isArray(parsed) ? parsed : [];
  list.push({
    document_type: documentType,
    file_path: filePath,
    original_filename: originalFilename,
    uploaded_at: nowSql(),
  });
  await query(`UPDATE users SET verification_documents = :json, updated_at = :now WHERE id = :id`, {
    json: JSON.stringify(list),
    now: nowSql(),
    id: userId,
  });
  return { documents: list };
}

/**
 * Tutor finished re-uploading the requested documents. Clear the resubmission
 * flags and send the account back to 'pending' so an admin re-reviews it
 * (parity with the website's Resubmit flow).
 */
export async function completeResubmission(userId) {
  await query(
    `UPDATE users
       SET needs_resubmission = 0,
           resubmission_message = NULL,
           resubmission_special_docs = NULL,
           resubmission_token = NULL,
           resubmission_token_expires = NULL,
           tutor_status = 'pending',
           updated_at = :now
     WHERE id = :id`,
    { now: nowSql(), id: userId }
  );
  return getMyProfile(userId);
}

export async function getAnalytics(userId) {
  // Profile views and contact clicks are counted as UNIQUE VISITOR IPs, not raw
  // row sums — same as the website's UsageTrackingModel::getUsageCount. Each row
  // stores the viewer's IP in metadata.visitor_ip; two visits from the same IP
  // count once, so the number reflects real distinct people, not refreshes.
  const [{ profile_views = 0 } = {}] = await query(
    `SELECT COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.visitor_ip'))) AS profile_views
       FROM usage_tracking WHERE user_id = :id AND metric_type = 'profile_views'`,
    { id: userId }
  );
  const [{ contact_clicks = 0 } = {}] = await query(
    `SELECT COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.visitor_ip'))) AS contact_clicks
       FROM usage_tracking WHERE user_id = :id AND metric_type = 'contact_clicks'`,
    { id: userId }
  );
  const [{ bookings = 0 } = {}] = await query(
    `SELECT COUNT(*) AS bookings FROM bookings WHERE tutor_id = :id`,
    { id: userId }
  );
  const [{ pending = 0 } = {}] = await query(
    `SELECT COUNT(*) AS pending FROM bookings WHERE tutor_id = :id AND status IN ('pending','inquiry')`,
    { id: userId }
  );
  const ratingRow = await queryOne(`SELECT rating, review_count FROM users WHERE id = :id`, { id: userId });
  const [{ favorites = 0 } = {}] = await query(
    `SELECT COUNT(*) AS favorites FROM favorites WHERE tutor_id = :id`,
    { id: userId }
  );

  return {
    profileViews: Number(profile_views),
    contactClicks: Number(contact_clicks),
    bookings: Number(bookings),
    pendingInquiries: Number(pending),
    favorites: Number(favorites),
    rating: Number(ratingRow?.rating ?? 0),
    reviewCount: Number(ratingRow?.review_count ?? 0),
  };
}

/**
 * Change the signed-in user's password.
 *
 * Verifies the current password, stores a fresh argon2id hash, then revokes
 * every existing refresh token — this signs out any OTHER device that had a
 * session (the security point of changing a password). To honour "stay logged
 * in on this device unless I log out", we immediately mint a fresh token pair
 * for the caller and hand it back, so the current app keeps working seamlessly
 * while the old tokens are dead.
 */
export async function changePassword(userId, { currentPassword, newPassword }) {
  const user = await queryOne(
    `SELECT id, username, email, role, first_name, last_name, is_active
       FROM users WHERE id = :id AND deleted_at IS NULL`,
    { id: userId }
  );
  if (!user) throw errors.unauthorized('Account no longer exists');

  const current = await queryOne(`SELECT password_hash FROM users WHERE id = :id`, { id: userId });
  const okCurrent = await verifyPassword(current?.password_hash, currentPassword);
  if (!okCurrent) throw errors.badRequest('Your current password is incorrect.');

  // Reject a no-op change so "changed successfully" always means something did.
  const sameAsOld = await verifyPassword(current?.password_hash, newPassword);
  if (sameAsOld) throw errors.badRequest('Your new password must be different from your current one.');

  const passwordHash = await hashPassword(newPassword);
  await query(`UPDATE users SET password_hash = :hash, updated_at = :now WHERE id = :id`, {
    hash: passwordHash,
    now: nowSql(),
    id: userId,
  });

  // Kill every session (other devices AND this one's old refresh token)...
  await query(
    `UPDATE refresh_tokens SET revoked_at = :now WHERE user_id = :id AND revoked_at IS NULL`,
    { now: nowSql(), id: userId }
  ).catch(() => {});

  // ...then re-issue for the current device so it stays logged in.
  const { accessToken, refreshToken } = await issueTokens(user, 'password-change');
  return { accessToken, refreshToken, message: 'Your password has been changed.' };
}

/**
 * The tutor's enquiry inbox — messages sent to them via the public contact form.
 * Read-only for the tutor (no in-app reply); they act on it by calling/WhatsApp-
 * ing the sender using the details captured with each enquiry.
 */
export async function getMyEnquiries(userId, { page = 1, limit = 20 } = {}) {
  const offset = (page - 1) * limit;
  const rows = await query(
    `SELECT id, sender_name, sender_email, sender_phone, subject, message,
            contact_preference, email_sent, is_read, created_at
       FROM tutor_enquiries
      WHERE tutor_id = :id
      ORDER BY created_at DESC
      LIMIT :limit OFFSET :offset`,
    { id: userId, limit, offset }
  );
  const [{ total = 0 } = {}] = await query(
    `SELECT COUNT(*) AS total FROM tutor_enquiries WHERE tutor_id = :id`,
    { id: userId }
  );
  const [{ unread = 0 } = {}] = await query(
    `SELECT COUNT(*) AS unread FROM tutor_enquiries WHERE tutor_id = :id AND is_read = 0`,
    { id: userId }
  );
  return {
    items: rows.map((r) => ({
      id: r.id,
      senderName: r.sender_name,
      senderEmail: r.sender_email,
      senderPhone: r.sender_phone,
      subject: r.subject,
      message: r.message,
      contactPreference: r.contact_preference,
      emailSent: !!r.email_sent,
      isRead: !!r.is_read,
      createdAt: r.created_at,
    })),
    total: Number(total),
    unread: Number(unread),
    page,
    limit,
  };
}

/** Mark one enquiry as read (only the owning tutor's own enquiries). */
export async function markEnquiryRead(userId, enquiryId) {
  const res = await query(
    `UPDATE tutor_enquiries SET is_read = 1 WHERE id = :eid AND tutor_id = :uid`,
    { eid: enquiryId, uid: userId }
  );
  if (!res.affectedRows) throw errors.notFound('Enquiry not found');
  return { id: enquiryId, isRead: true };
}
