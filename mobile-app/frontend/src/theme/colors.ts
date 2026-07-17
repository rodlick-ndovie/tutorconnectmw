// Palette adapted from the TutorConnect Malawi website (app/Views/layout/main.php):
//   primary  #E55C0D (orange)   secondary #2C3E50 (slate)
//   accent   #34495E            neutral   #ECF0F1
export const colors = {
  primary: '#E55C0D',
  primaryDark: '#c94609',
  primaryBg: '#FDECE0', // light orange tint for chips/badges/pills
  secondary: '#2C3E50',
  accent: '#34495E',
  neutral: '#ECF0F1',
  success: '#16A34A',
  successBg: '#DCFCE7',
  star: '#F59E0B',
  bg: '#FFFFFF',
  surface: '#F8FAFC',
  border: '#E5E7EB',
  text: '#1F2937',
  textMuted: '#64748B',
  textLight: '#94A3B8',
  danger: '#EF4444',
  white: '#FFFFFF',
  // Hex (no leading #) for building ui-avatars.com fallback URLs.
  avatarBg: 'E55C0D',
} as const;

// Gradient stops matching the website's hero/button gradients.
export const gradients = {
  hero: ['#2C3E50', '#34495E'] as const,
  primary: ['#E55C0D', '#c94609'] as const,
};

export const spacing = { xs: 4, sm: 8, md: 12, lg: 16, xl: 24, xxl: 32 };
export const radius = { sm: 8, md: 12, lg: 16, xl: 24, pill: 999 };
