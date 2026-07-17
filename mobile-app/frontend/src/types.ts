export interface ApiEnvelope<T> {
  success: boolean;
  data: T;
  meta?: { page?: number; limit?: number; total?: number; unread?: number };
  error?: { code: string; message: string };
}

export interface AuthUser {
  id: number;
  username: string;
  email: string;
  role: 'customer' | 'trainer' | 'admin' | 'sub-admin';
  firstName: string | null;
  lastName: string | null;
  phone: string | null;
  district: string | null;
  profilePicture: string | null;
  coverPhoto: string | null;
  isVerified: boolean;
  tutorStatus: string | null;
}

export interface TutorCard {
  id: number;
  name: string;
  username: string;
  profilePicture: string | null;
  district: string | null;
  location: string | null;
  teachingMode: string | null;
  experienceYears: number | null;
  schoolName: string | null;
  rating: number;
  reviewCount: number;
  featured: boolean;
  planName: string | null;
  badgeLevel: string;
  curricula: string[];
  subjects: string[];
}

export interface TutorProfile extends TutorCard {
  bio: string | null;
  bioVideo: string | null;
  coverPhoto: string | null;
  bestCallTime: string | null;
  whatsappNumber: string | null;
  phone: string | null;
  email: string | null;
  structuredSubjects: Record<string, { levels: Record<string, string[]> }>;
  availability: { days?: string[]; times?: string[] };
}

export interface Review {
  id: number;
  reviewerName: string;
  rating: number;
  comment: string | null;
  createdAt: string;
}

export interface Notice {
  id: number;
  schoolName: string;
  noticeType: 'Vacancy' | 'Notice' | 'Announcement';
  title: string;
  content: string;
  image: string | null;
  createdAt: string;
}

export interface ProfileCompletionItem {
  key: string;
  label: string;
  done: boolean;
  isDocument?: boolean;
}

export interface ProfileCompletion {
  items: ProfileCompletionItem[];
  percent: number;
  doneCount: number;
  totalCount: number;
  allSubmitted: boolean;
}

export interface MyProfile {
  completion: ProfileCompletion;
  id: number;
  username: string;
  email: string;
  role: string;
  firstName: string | null;
  lastName: string | null;
  gender: string | null;
  phone: string | null;
  district: string | null;
  location: string | null;
  experienceYears: number | null;
  teachingMode: string | null;
  bio: string | null;
  bioVideo: string | null;
  profilePicture: string | null;
  coverPhoto: string | null;
  uploadedDocuments: string[];
  whatsappNumber: string | null;
  phoneVisible: boolean;
  emailVisible: boolean;
  bestCallTime: string | null;
  preferredContactMethod: string | null;
  isEmployed: boolean;
  schoolName: string | null;
  tutorStatus: string | null;
  needsResubmission: boolean;
  resubmissionMessage: string | null;
  resubmissionDocs: { document_type: string; resubmission_message?: string }[];
  isVerified: boolean;
  registrationCompleted: boolean;
  subscriptionPlan: string | null;
  subscriptionExpiresAt: string | null;
  rating: number;
  reviewCount: number;
  structuredSubjects: Record<string, { levels: Record<string, string[]> }>;
  availability: { days?: string[]; times?: string[] };
}

export interface Analytics {
  profileViews: number;
  contactClicks: number;
  bookings: number;
  pendingInquiries: number;
  favorites: number;
  rating: number;
  reviewCount: number;
}

export interface Plan {
  id: number;
  name: string;
  description: string | null;
  priceMonthly: number;
  badgeLevel: string;
  searchRanking: string;
  features: {
    maxSubjects: number;
    maxReviews: number;
    maxMessages: number;
    showWhatsapp: boolean;
    allowVideoUpload: boolean;
    allowPdfUpload: boolean;
    allowAnnouncements: boolean;
    districtSpotlightDays: number;
  };
  sortOrder: number;
}

export interface Subscription {
  id: number;
  planId: number;
  planName: string;
  status: string;
  billingMonths: number;
  currentPeriodStart: string;
  currentPeriodEnd: string;
  paymentStatus: string;
  paymentAmount: number | null;
  isActive: boolean;
  maxSubjects: number; // 0 = unlimited
}

export interface Enquiry {
  id: number;
  senderName: string;
  senderEmail: string;
  senderPhone: string | null;
  subject: string;
  message: string;
  contactPreference: string | null;
  emailSent: boolean;
  isRead: boolean;
  createdAt: string;
}

export interface PastPaper {
  id: number;
  examBody: string;
  examLevel: string;
  subject: string;
  year: number;
  title: string | null;
  paperCode: string | null;
  fileSize: string | null;
  downloadCount: number;
  isPaid: boolean;
  price: number;
  copyrightNotice: string | null;
  fileUrl: string | null;
}

export interface VideoSolution {
  id: number;
  tutorId: number;
  tutorName: string | null;
  title: string;
  description: string | null;
  platform: 'youtube' | 'vimeo' | null;
  videoId: string | null;
  embedCode: string | null;
  examBody: string | null;
  subject: string | null;
  topic: string | null;
  viewCount: number;
  createdAt: string;
}

// ---- University & College support ----
export type UniAccountType = 'individual' | 'firm';

export interface UniTutor {
  id: number;
  referenceCode: string;
  accountType: UniAccountType;
  name: string;
  profilePicture: string | null;
  teachingMode: string;
  cityLocation: string;
  yearOfStudyOrGraduation: string;
  institutions: string[];
  specializations: string[];
  serviceAreas: string[];
  status: string;
}

export interface UniTutorProfile extends UniTutor {
  bio: string;
  email: string;
  phone: string;
  workStatus: string | null;
  employerName: string | null;
  availableDays: string[];
  preferredTimes: string[];
  references: unknown[];
  rates: {
    hourly: number | null;
    consultation: number | null;
    dissertation: number | null;
    examPreparation: number | null;
  };
  subscriptionPlan: string;
}

export interface UniMeta {
  serviceCategories: Record<string, string[]>;
  teachingModes: string[];
  workStatusOptions: string[];
  accountTypes: UniAccountType[];
  days: string[];
  preferredTimes: string[];
  plans: string[];
}

export interface LectureRequest {
  id: number;
  referenceCode: string;
  institution: string;
  serviceCategory: string;
  topic: string;
  deliveryMode: string;
  cityLocation: string;
  preferredDate: string | null;
  preferredTime: string | null;
  budgetRange: string | null;
  notes: string | null;
  status: string;
  createdAt: string;
}

export interface ParentRequest {
  id: number;
  referenceCode: string;
  curriculum: string;
  gradeClass: string;
  subjects: string[];
  district: string;
  specificLocation: string | null;
  mode: string;
  budgetMin: number | null;
  budgetMax: number | null;
  budgetPeriod: string | null;
  notes: string | null;
  status: string;
  createdAt: string;
}

export interface NotificationItem {
  id: number;
  type: string;
  title: string;
  body: string | null;
  data: Record<string, unknown> | null;
  read: boolean;
  createdAt: string;
}

export interface TutorFilters {
  q?: string;
  district?: string;
  curriculum?: string;
  level?: string;
  subject?: string;
  teaching_mode?: string;
  sort?: 'rating' | 'experience' | 'reviews';
}
