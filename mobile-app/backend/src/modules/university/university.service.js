import crypto from 'node:crypto';
import { pool, query, queryOne } from '../../config/db.js';
import { errors } from '../../utils/http.js';
import { nowSql, sqlInMinutes } from '../../utils/time.js';
import { mediaUrl, safeJsonParse } from '../../utils/media.js';
import { hashPassword } from '../../utils/password.js';
import { sendMail } from '../../utils/mailer.js';

const OTP_TTL_MINUTES = 10;
const genOtp = () => String(Math.floor(100000 + Math.random() * 900000));
const ref = (prefix) =>
  `${prefix}-${new Date().toISOString().slice(0, 10).replace(/-/g, '')}-${crypto
    .randomBytes(3)
    .toString('hex')
    .toUpperCase()}`;

/** Public shape of a university/college tutor (or firm). */
function present(t, { full = false } = {}) {
  const base = {
    id: t.id,
    referenceCode: t.reference_code,
    accountType: t.account_type || 'individual',
    name: t.full_name,
    profilePicture: mediaUrl(t.profile_picture),
    teachingMode: t.teaching_mode,
    cityLocation: t.city_location,
    yearOfStudyOrGraduation: t.year_of_study_or_graduation,
    institutions: safeJsonParse(t.institutions_json, []),
    specializations: safeJsonParse(t.specializations_json, []),
    serviceAreas: safeJsonParse(t.service_areas_json, []),
    status: t.status,
  };
  if (!full) return base;

  return {
    ...base,
    bio: t.bio,
    email: t.email,
    phone: t.phone,
    workStatus: t.work_status,
    employerName: t.employer_name,
    availableDays: safeJsonParse(t.available_days_json, []),
    preferredTimes: safeJsonParse(t.preferred_times_json, []),
    references: safeJsonParse(t.references_json, []),
    rates: {
      hourly: t.hourly_rate === null ? null : Number(t.hourly_rate),
      consultation: t.consultation_package_rate === null ? null : Number(t.consultation_package_rate),
      dissertation: t.dissertation_package_rate === null ? null : Number(t.dissertation_package_rate),
      examPreparation: t.exam_preparation_rate === null ? null : Number(t.exam_preparation_rate),
    },
    subscriptionPlan: t.subscription_plan,
  };
}

/** Approved university tutors / firms, with optional filters. */
export async function listUniversityTutors(filters, { page, limit }) {
  const offset = (page - 1) * limit;
  const conds = [`status = 'approved'`];
  const params = { limit, offset };

  if (filters.accountType) {
    conds.push('account_type = :accountType');
    params.accountType = filters.accountType;
  }
  if (filters.q) {
    conds.push('(full_name LIKE :q OR bio LIKE :q)');
    params.q = `%${filters.q}%`;
  }
  if (filters.category) {
    // specializations / service areas are JSON-in-TEXT arrays
    conds.push('(specializations_json LIKE :cat OR service_areas_json LIKE :cat)');
    params.cat = `%${filters.category}%`;
  }
  if (filters.teachingMode) {
    conds.push('teaching_mode = :mode');
    params.mode = filters.teachingMode;
  }

  const where = conds.join(' AND ');
  const rows = await query(
    `SELECT * FROM university_college_tutors WHERE ${where}
     ORDER BY FIELD(subscription_plan,'Premium','Standard','Basic'), created_at DESC
     LIMIT :limit OFFSET :offset`,
    params
  );
  const countRow = await queryOne(
    `SELECT COUNT(*) AS total FROM university_college_tutors WHERE ${where}`,
    params
  );
  return { items: rows.map((r) => present(r)), total: Number(countRow?.total ?? 0) };
}

export async function getUniversityTutor(id) {
  const row = await queryOne(
    `SELECT * FROM university_college_tutors WHERE id = :id AND status = 'approved'`,
    { id }
  );
  if (!row) throw errors.notFound('University tutor not found');
  return present(row, { full: true });
}

/** The signed-in user's own university profile (portal). */
export async function getMyUniversityProfile(user) {
  const row = await queryOne(
    `SELECT * FROM university_college_tutors
     WHERE user_id = :id OR email = :email OR username = :username LIMIT 1`,
    { id: user.id, email: user.email, username: user.username }
  );
  if (!row) throw errors.notFound('No university profile for this account');
  return present(row, { full: true });
}

/**
 * Register a university/college tutor OR a firm (company offering services).
 * Creates BOTH a users row (so they can log in, verified by OTP) and the
 * university_college_tutors record the website/admin reads.
 */
export async function registerUniversity(input, files) {
  const email = input.email.trim().toLowerCase();
  const username = input.username.trim();

  const dupUser = await queryOne(
    `SELECT id, email FROM users WHERE (email = :email OR username = :username) AND deleted_at IS NULL LIMIT 1`,
    { email, username }
  );
  if (dupUser) {
    throw errors.conflict(
      dupUser.email === email
        ? 'That email is already registered.'
        : 'That username is already taken.'
    );
  }
  const dupUni = await queryOne(
    `SELECT id FROM university_college_tutors WHERE email = :email LIMIT 1`,
    { email }
  );
  if (dupUni) throw errors.conflict('That email is already registered for university support.');

  if (!files?.profilePhoto) throw errors.badRequest('A profile photo is required.');
  if (!files?.nationalId) throw errors.badRequest('A national ID document is required.');

  const now = nowSql();
  const code = genOtp();
  const passwordHash = await hashPassword(input.password);
  const referenceCode = ref('UNI');

  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    // 1. Login account (OTP-verified like a normal tutor).
    const [userRes] = await conn.execute(
      `INSERT INTO users
         (username, email, password_hash, role, first_name, last_name, phone, district,
          tutor_status, is_verified, is_active, registration_completed, terms_accepted,
          otp_code, otp_expires_at, created_at, updated_at)
       VALUES
         (:username, :email, :passwordHash, 'trainer', :firstName, :lastName, :phone, :district,
          'pending', 0, 1, 0, 1,
          :code, :otpExpires, :now, :now)`,
      {
        username,
        email,
        passwordHash,
        firstName: input.firstName,
        lastName: input.lastName,
        phone: input.phone,
        district: input.district ?? null,
        code,
        otpExpires: sqlInMinutes(OTP_TTL_MINUTES),
        now,
      }
    );
    const userId = userRes.insertId;

    // 2. The university/college record (what the directory + admin read).
    await conn.execute(
      `INSERT INTO university_college_tutors
         (reference_code, full_name, email, phone, profile_picture, national_id_file,
          certification_files_json, institutions_json, specializations_json, service_areas_json,
          year_of_study_or_graduation, bio, references_json, work_status, employer_name,
          employer_contact, available_days_json, preferred_times_json, teaching_mode,
          city_location, hourly_rate, consultation_package_rate, dissertation_package_rate,
          exam_preparation_rate, subscription_plan, status, created_at, updated_at,
          user_id, username, account_type)
       VALUES
         (:referenceCode, :fullName, :email, :phone, :profilePicture, :nationalIdFile,
          :certs, :institutions, :specializations, :serviceAreas,
          :year, :bio, :references, :workStatus, :employerName,
          :employerContact, :days, :times, :teachingMode,
          :city, :hourly, :consultation, :dissertation,
          :exam, :plan, 'pending', :now, :now,
          :userId, :username, :accountType)`,
      {
        referenceCode,
        fullName: input.fullName,
        email,
        phone: input.phone,
        profilePicture: files.profilePhoto,
        nationalIdFile: files.nationalId,
        certs: JSON.stringify(files.certificates ?? []),
        institutions: JSON.stringify(input.institutions ?? []),
        specializations: JSON.stringify(input.specializations ?? []),
        serviceAreas: JSON.stringify(input.serviceAreas ?? []),
        year: input.yearOfStudyOrGraduation,
        bio: input.bio,
        references: JSON.stringify(input.references ?? []),
        workStatus: input.workStatus ?? null,
        employerName: input.employerName ?? null,
        employerContact: input.employerContact ?? null,
        days: JSON.stringify(input.availableDays ?? []),
        times: JSON.stringify(input.preferredTimes ?? []),
        teachingMode: input.teachingMode,
        city: input.cityLocation,
        hourly: input.hourlyRate ?? null,
        consultation: input.consultationRate ?? null,
        dissertation: input.dissertationRate ?? null,
        exam: input.examPreparationRate ?? null,
        plan: input.subscriptionPlan ?? 'Basic',
        now,
        userId,
        username,
        accountType: input.accountType === 'firm' ? 'firm' : 'individual',
      }
    );

    await conn.commit();
  } catch (e) {
    await conn.rollback();
    throw e;
  } finally {
    conn.release();
  }

  // Fire-and-forget — SMTP is slow and must not hold up the response.
  sendMail({
    to: email,
    subject: 'Your TutorConnect Malawi verification code',
    html: `<h2>Verify your email</h2>
      <p>Hi ${input.firstName || input.fullName}, thanks for registering for University &amp; College Support.</p>
      <p>Your verification code is:</p>
      <p style="font-size:28px;font-weight:bold;letter-spacing:4px">${code}</p>
      <p>This code expires in ${OTP_TTL_MINUTES} minutes.</p>
      <p>Reference: <strong>${referenceCode}</strong></p>`,
  }).catch((e) => console.error('[university] OTP email failed:', e.message));

  return {
    email,
    referenceCode,
    message:
      'Registration received. Verify your email with the 6-digit code, then an admin will review your documents.',
  };
}

// ---- Lecture / service requests ----

export async function createLectureRequest(input) {
  const now = nowSql();
  const referenceCode = ref('ULR');
  const res = await query(
    `INSERT INTO university_lecture_requests
       (reference_code, full_name, email, phone, institution, service_category, topic,
        delivery_mode, city_location, preferred_date, preferred_time, budget_range, notes,
        status, created_at, updated_at)
     VALUES
       (:referenceCode, :fullName, :email, :phone, :institution, :category, :topic,
        :mode, :city, :date, :time, :budget, :notes,
        'pending', :now, :now)`,
    {
      referenceCode,
      fullName: input.fullName,
      email: input.email.trim().toLowerCase(),
      phone: input.phone,
      institution: input.institution,
      category: input.serviceCategory,
      topic: input.topic,
      mode: input.deliveryMode,
      city: input.cityLocation,
      date: input.preferredDate ?? null,
      time: input.preferredTime ?? null,
      budget: input.budgetRange ?? null,
      notes: input.notes ?? null,
      now,
    }
  );
  return {
    id: res.insertId,
    referenceCode,
    message: 'Request submitted. Matching tutors will be notified and will contact you.',
  };
}

/** Open requests a university tutor can apply to. */
export async function listLectureRequests({ page, limit }) {
  const offset = (page - 1) * limit;
  const rows = await query(
    `SELECT id, reference_code, institution, service_category, topic, delivery_mode,
            city_location, preferred_date, preferred_time, budget_range, notes, status, created_at
     FROM university_lecture_requests
     WHERE status IN ('pending','open','matched')
     ORDER BY created_at DESC LIMIT :limit OFFSET :offset`,
    { limit, offset }
  );
  const countRow = await queryOne(
    `SELECT COUNT(*) AS total FROM university_lecture_requests WHERE status IN ('pending','open','matched')`
  );
  return {
    items: rows.map((r) => ({
      id: r.id,
      referenceCode: r.reference_code,
      institution: r.institution,
      serviceCategory: r.service_category,
      topic: r.topic,
      deliveryMode: r.delivery_mode,
      cityLocation: r.city_location,
      preferredDate: r.preferred_date,
      preferredTime: r.preferred_time,
      budgetRange: r.budget_range,
      notes: r.notes,
      status: r.status,
      createdAt: r.created_at,
    })),
    total: Number(countRow?.total ?? 0),
  };
}

/** A university tutor applies to (accepts) a request. */
export async function applyToLectureRequest(user, requestId) {
  const me = await queryOne(
    `SELECT id, email, status FROM university_college_tutors
     WHERE user_id = :id OR email = :email OR username = :username LIMIT 1`,
    { id: user.id, email: user.email, username: user.username }
  );
  if (!me) throw errors.forbidden('Only registered university tutors can apply.');
  if (me.status !== 'approved') throw errors.forbidden('Your university profile is pending approval.');

  const req = await queryOne(`SELECT id FROM university_lecture_requests WHERE id = :id`, {
    id: requestId,
  });
  if (!req) throw errors.notFound('Request not found');

  const existing = await queryOne(
    `SELECT id FROM university_lecture_request_applications
     WHERE university_lecture_request_id = :rid AND university_tutor_id = :tid LIMIT 1`,
    { rid: requestId, tid: me.id }
  );
  if (existing) throw errors.conflict('You have already applied to this request.');

  const now = nowSql();
  await query(
    `INSERT INTO university_lecture_request_applications
       (university_lecture_request_id, university_tutor_id, tutor_email, status, accepted_at, created_at, updated_at)
     VALUES (:rid, :tid, :email, 'accepted', :now, :now, :now)`,
    { rid: requestId, tid: me.id, email: me.email, now }
  );
  return { applied: true, requestId };
}
