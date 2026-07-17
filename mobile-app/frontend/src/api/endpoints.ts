import { api, unwrap } from './client';
import type {
  AuthUser,
  TutorCard,
  TutorProfile,
  Review,
  Notice,
  TutorFilters,
  MyProfile,
  Analytics,
  Plan,
  Subscription,
  PastPaper,
  VideoSolution,
  NotificationItem,
  UniTutor,
  UniTutorProfile,
  UniMeta,
  UniAccountType,
  LectureRequest,
  ParentRequest,
  Enquiry,
} from '../types';

// ---- Auth (tutors log in / register; students browse without an account) ----
// Mirrors the website's two-step tutor sign-up.
export type RegisterBody = {
  // Step 1 — personal details
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  gender?: string;
  district?: string;
  location?: string;
  isEmployed?: boolean;
  schoolName?: string;
  // Step 2 — account
  username: string;
  password: string;
};

export type AvailabilityResult = {
  email?: { available: boolean; message: string };
  username?: { available: boolean; message: string };
  phone?: { available: boolean; message: string };
};

export const authApi = {
  login: (login: string, password: string) =>
    unwrap<{ user: AuthUser; accessToken: string; refreshToken: string }>(
      api.post('/auth/login', { login, password })
    ),
  register: (body: RegisterBody) =>
    unwrap<{ email: string; message: string }>(api.post('/auth/register', body)),
  checkAvailability: (params: { email?: string; username?: string; phone?: string }) =>
    unwrap<AvailabilityResult>(api.get('/auth/check-availability', { params })),
  verifyOtp: (email: string, code: string) =>
    unwrap<{ user: AuthUser; accessToken: string; refreshToken: string }>(
      api.post('/auth/verify-otp', { email, code })
    ),
  resendOtp: (email: string) =>
    unwrap<{ email: string; message: string }>(api.post('/auth/resend-otp', { email })),
  forgotPassword: (email: string) =>
    unwrap<{ email: string; message: string }>(api.post('/auth/forgot-password', { email })),
  resetPassword: (email: string, code: string, newPassword: string) =>
    unwrap<{ message: string }>(api.post('/auth/reset-password', { email, code, newPassword })),
  me: () => unwrap<AuthUser>(api.get('/auth/me')),
  logout: (refreshToken: string) => api.post('/auth/logout', { refreshToken }),
};

// ---- Tutors (public discovery) ----
export const tutorsApi = {
  search: async (filters: TutorFilters, page = 1, limit = 20) => {
    const res = await api.get('/tutors', { params: { ...filters, page, limit } });
    return { items: res.data.data as TutorCard[], meta: res.data.meta };
  },
  get: (idOrSlug: string | number) => unwrap<TutorProfile>(api.get(`/tutors/${idOrSlug}`)),
  reviews: (id: number, page = 1) =>
    unwrap<Review[]>(api.get(`/tutors/${id}/reviews`, { params: { page } })),
  // Public — no account needed (mirrors the website's review form).
  addReview: (
    id: number,
    body: { reviewerName: string; reviewerEmail?: string; rating: number; comment?: string; isAnonymous?: boolean }
  ) => unwrap<Review>(api.post(`/tutors/${id}/reviews`, body)),
  // Public — emails the tutor + records the contact (mirrors Home::sendMessage).
  contact: (
    id: number,
    body: {
      senderName: string;
      senderEmail: string;
      subject: string;
      message: string;
      contactPreference?: string;
      contactDetail?: string;
    }
  ) => unwrap<{ sent: boolean; message: string }>(api.post(`/tutors/${id}/contact`, body)),
  // Fire-and-forget analytics when a visitor taps WhatsApp/Call/Email.
  trackContact: (id: number, method: 'whatsapp' | 'call' | 'email') =>
    api.post(`/tutors/${id}/track-contact`, { method }).catch(() => {}),
};

// ---- Meta (filter options) ----
export const metaApi = {
  districts: () => unwrap<string[]>(api.get('/meta/districts')),
  curricula: () => unwrap<string[]>(api.get('/meta/curricula')),
  levels: (curriculum?: string) =>
    unwrap<string[]>(api.get('/meta/levels', { params: { curriculum } })),
  subjects: (curriculum?: string, level?: string) =>
    unwrap<string[]>(api.get('/meta/subjects', { params: { curriculum, level } })),
};

// ---- Notices ("Success Stories") ----
export const noticesApi = {
  list: (type?: string) => unwrap<Notice[]>(api.get('/notices', { params: { type } })),
  get: (id: number) => unwrap<Notice>(api.get(`/notices/${id}`)),
};

// ---- Tutor self-service (authenticated) ----
export type ProfileUpdate = Partial<{
  firstName: string;
  lastName: string;
  phone: string;
  gender: string;
  district: string;
  location: string;
  experienceYears: number;
  teachingMode: string;
  bio: string;
  whatsappNumber: string;
  phoneVisible: boolean;
  emailVisible: boolean;
  bestCallTime: string;
  preferredContactMethod: string;
  isEmployed: boolean;
  schoolName: string;
}>;

// A local file selected on the device, ready for multipart upload.
export type UploadFile = { uri: string; name: string; type: string };

// File uploads are far bigger than JSON calls and go over Wi-Fi, so the normal
// 30s timeout was being hit and surfacing as a confusing "Network Error".
const UPLOAD_TIMEOUT_MS = 120_000;

function uploadMultipart<T>(url: string, file: UploadFile): Promise<T> {
  const form = new FormData();
  // React Native's FormData accepts a { uri, name, type } object for files.
  form.append('file', file as unknown as Blob);
  return unwrap<T>(
    api.post(url, form, {
      headers: { 'Content-Type': 'multipart/form-data' },
      timeout: UPLOAD_TIMEOUT_MS,
    })
  );
}

// ---- Uploads (tutor, authenticated) — images written to the website uploads dir ----
export const uploadsApi = {
  profilePhoto: (file: UploadFile) => uploadMultipart<MyProfile>('/me/profile-photo', file),
  coverPhoto: (file: UploadFile) => uploadMultipart<MyProfile>('/me/cover-photo', file),
  document: (file: UploadFile, type: string) =>
    uploadMultipart<{ documents: unknown[] }>(
      `/me/documents?type=${encodeURIComponent(type)}`,
      file
    ),
};

export const meApi = {
  profile: () => unwrap<MyProfile>(api.get('/me/profile')),
  updateProfile: (body: ProfileUpdate) => unwrap<MyProfile>(api.patch('/me/profile', body)),
  updateAvailability: (availability: { days: string[]; times: string[] }) =>
    unwrap(api.put('/me/availability', availability)),
  updateSubjects: (structuredSubjects: Record<string, { levels: Record<string, string[]> }>) =>
    unwrap(api.put('/me/subjects', structuredSubjects)),
  analytics: () => unwrap<Analytics>(api.get('/me/analytics')),
  // Tutor enquiry inbox (read-only).
  enquiries: async (page = 1) => {
    const res = await api.get('/me/enquiries', { params: { page } });
    return res.data.data as { items: Enquiry[]; total: number; unread: number; page: number; limit: number };
  },
  markEnquiryRead: (id: number) => unwrap(api.post(`/me/enquiries/${id}/read`)),
  // Returns a fresh token pair: changing the password revokes all sessions, so
  // the caller must re-store these to keep THIS device logged in.
  changePassword: (currentPassword: string, newPassword: string) =>
    unwrap<{ accessToken: string; refreshToken: string; message: string }>(
      api.post('/me/change-password', { currentPassword, newPassword })
    ),
  completeResubmission: () => unwrap<MyProfile>(api.post('/me/resubmit')),
  subscription: () => unwrap<Subscription | null>(api.get('/me/subscription')),
  checkout: (planId: number, billingMonths = 1) =>
    unwrap<{ subscriptionId: number; txRef: string; amount: number; checkoutUrl?: string; free?: boolean }>(
      api.post('/me/subscription/checkout', { planId, billingMonths })
    ),
};

export const plansApi = {
  list: () => unwrap<Plan[]>(api.get('/plans')),
};

// ---- Resources (public) ----
export const resourcesApi = {
  pastPapers: async (params: { subject?: string; exam_body?: string; page?: number } = {}) => {
    const res = await api.get('/resources/past-papers', { params });
    return { items: res.data.data as PastPaper[], meta: res.data.meta };
  },
  pastPaper: (id: number) => unwrap<PastPaper>(api.get(`/resources/past-papers/${id}`)),
  videos: async (params: { subject?: string; exam_body?: string; page?: number } = {}) => {
    const res = await api.get('/resources/videos', { params });
    return { items: res.data.data as VideoSolution[], meta: res.data.meta };
  },
  video: (id: number) => unwrap<VideoSolution>(api.get(`/resources/videos/${id}`)),
  // Buy a paid past paper (public — no account). Returns a PayChangu checkout URL.
  purchasePaper: (
    id: number,
    buyer: { buyerName: string; buyerEmail: string; buyerPhone?: string }
  ) =>
    unwrap<{ txRef: string; accessToken: string; amount: number; checkoutUrl?: string }>(
      api.post(`/resources/past-papers/${id}/purchase`, buyer)
    ),
  purchaseStatus: (txRef: string) =>
    unwrap<{ status: 'paid' | 'pending'; accessToken?: string; downloadUrl?: string }>(
      api.get(`/resources/past-papers/purchase/${encodeURIComponent(txRef)}/status`)
    ),
};

// ---- Parent requests ("Request a Tutor") ----
export type ParentRequestBody = {
  curriculum: string;
  gradeClass: string;
  subjects: string[];
  district: string;
  specificLocation?: string;
  mode: 'online' | 'in-person' | 'both';
  budgetMin?: number;
  budgetMax?: number;
  budgetPeriod?: string;
  notes?: string;
  parentPhone: string;
  parentEmail: string;
};

export const parentRequestsApi = {
  list: async (params: { district?: string; curriculum?: string; page?: number } = {}) => {
    const res = await api.get('/parent-requests', { params });
    return { items: res.data.data as ParentRequest[], meta: res.data.meta };
  },
  get: (id: number) => unwrap<ParentRequest>(api.get(`/parent-requests/${id}`)),
  create: (body: ParentRequestBody) =>
    unwrap<{ id: number; referenceCode: string }>(api.post('/parent-requests', body)),
  apply: (id: number) => unwrap(api.post(`/parent-requests/${id}/apply`)),
};

// ---- University & College support (individuals + firms/companies) ----
export type UniRegisterBody = {
  accountType: UniAccountType;
  fullName: string;
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  username: string;
  password: string;
  district?: string;
  cityLocation: string;
  yearOfStudyOrGraduation: string;
  bio: string;
  teachingMode: string;
  workStatus?: string;
  employerName?: string;
  subscriptionPlan?: string;
  hourlyRate?: string;
  institutions?: string[];
  specializations?: string[];
  serviceAreas?: string[];
  availableDays?: string[];
  preferredTimes?: string[];
};

export type LectureRequestBody = {
  fullName: string;
  email: string;
  phone: string;
  institution: string;
  serviceCategory: string;
  topic: string;
  deliveryMode: string;
  cityLocation: string;
  preferredDate?: string;
  preferredTime?: string;
  budgetRange?: string;
  notes?: string;
};

export const universityApi = {
  meta: () => unwrap<UniMeta>(api.get('/university/meta')),

  tutors: async (
    filters: { accountType?: UniAccountType; category?: string; teachingMode?: string; q?: string } = {},
    page = 1,
    limit = 20
  ) => {
    const res = await api.get('/university/tutors', { params: { ...filters, page, limit } });
    return { items: res.data.data as UniTutor[], meta: res.data.meta };
  },
  tutor: (id: number) => unwrap<UniTutorProfile>(api.get(`/university/tutors/${id}`)),

  // Registration needs files, so it goes up as multipart.
  register: (body: UniRegisterBody, files: { profilePhoto: UploadFile; nationalId: UploadFile; certificates?: UploadFile[] }) => {
    const form = new FormData();
    Object.entries(body).forEach(([k, v]) => {
      if (v === undefined) return;
      form.append(k, Array.isArray(v) ? JSON.stringify(v) : String(v));
    });
    form.append('profilePhoto', files.profilePhoto as unknown as Blob);
    form.append('nationalId', files.nationalId as unknown as Blob);
    (files.certificates ?? []).forEach((c) => form.append('certificates', c as unknown as Blob));
    return unwrap<{ email: string; referenceCode: string; message: string }>(
      api.post('/university/register', form, {
        headers: { 'Content-Type': 'multipart/form-data' },
        timeout: UPLOAD_TIMEOUT_MS, // several files at once
      })
    );
  },

  // Public: request a lecture / service.
  requestLecture: (body: LectureRequestBody) =>
    unwrap<{ id: number; referenceCode: string; message: string }>(
      api.post('/university/lecture-requests', body)
    ),

  // Portal (authenticated university tutor)
  me: () => unwrap<UniTutorProfile>(api.get('/university/me')),
  openRequests: async (page = 1) => {
    const res = await api.get('/university/lecture-requests', { params: { page } });
    return { items: res.data.data as LectureRequest[], meta: res.data.meta };
  },
  applyToRequest: (id: number) =>
    unwrap<{ applied: boolean }>(api.post(`/university/lecture-requests/${id}/apply`)),
};

// ---- Devices (push notifications, tutor/admin) ----
export const devicesApi = {
  register: (expoPushToken: string, platform: string) =>
    unwrap(api.post('/devices', { expoPushToken, platform })),
  remove: (token: string) => unwrap(api.delete(`/devices/${encodeURIComponent(token)}`)),
};

// ---- Payments ----
export const paymentsApi = {
  status: (txRef: string) =>
    unwrap<{ txRef: string; status: string; subscriptionId: number }>(
      api.get(`/payments/${encodeURIComponent(txRef)}/status`)
    ),
};

// ---- Notifications (in-app feed, tutor/admin) ----
export const notificationsApi = {
  list: async (page = 1) => {
    const res = await api.get('/notifications', { params: { page } });
    return { items: res.data.data as NotificationItem[], meta: res.data.meta };
  },
  markRead: (id: number) => unwrap(api.patch(`/notifications/${id}/read`)),
  markAllRead: () => unwrap(api.patch('/notifications/read-all')),
};
