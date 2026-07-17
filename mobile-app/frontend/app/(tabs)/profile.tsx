import { View, Text, StyleSheet, Pressable, ScrollView, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Redirect, useRouter, type Href } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { useAuth } from '../../src/store/auth';
import { useRefresh } from '../../src/hooks/useRefresh';
import { meApi } from '../../src/api/endpoints';
import { Avatar } from '../../src/components/Avatar';
import { Skeleton } from '../../src/components/skeletons';
import type { AuthUser, ProfileCompletion } from '../../src/types';
import { colors, radius, spacing } from '../../src/theme/colors';

export default function Account() {
  const { user, status, logout } = useAuth();

  if (status === 'loading') {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  // Logging in happens on a dedicated full-screen route (no tab bar / header).
  if (status !== 'authenticated' || !user) {
    return <Redirect href="/login" />;
  }

  return <Dashboard user={user} onLogout={logout} />;
}

const STATUS_META: Record<string, { label: string; color: string; bg: string; icon: keyof typeof Ionicons.glyphMap }> = {
  approved: { label: 'Approved', color: colors.success, bg: colors.successBg, icon: 'checkmark-circle' },
  pending: { label: 'Pending approval', color: '#B45309', bg: '#FEF3C7', icon: 'time' },
  suspended: { label: 'Suspended', color: colors.danger, bg: '#FEE2E2', icon: 'alert-circle' },
  inactive: { label: 'Inactive', color: colors.textMuted, bg: colors.surface, icon: 'pause-circle' },
};

function Dashboard({ user, onLogout }: { user: AuthUser; onLogout: () => void }) {
  const router = useRouter();
  const isTutor = user.role === 'trainer';
  const isApproved = user.tutorStatus === 'approved';

  const profile = useQuery({ queryKey: ['me', 'profile'], queryFn: meApi.profile, enabled: isTutor });
  // Analytics is gated server-side to approved tutors — don't call it otherwise.
  const analytics = useQuery({
    queryKey: ['me', 'analytics'],
    queryFn: meApi.analytics,
    enabled: isTutor && isApproved,
  });

  const meta = STATUS_META[user.tutorStatus ?? ''] ?? null;
  const plan = profile.data?.subscriptionPlan;
  const { refreshing, onRefresh } = useRefresh(profile.refetch, analytics.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <ScrollView
        contentContainerStyle={styles.content}
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
      >
        {/* Identity */}
        <View style={styles.header}>
          <Avatar
            uri={user.profilePicture}
            name={`${user.firstName ?? ''} ${user.lastName ?? ''}`.trim() || 'U'}
            size={64}
          />
          <View style={{ flex: 1 }}>
            <Text style={styles.name} numberOfLines={1}>
              {user.firstName} {user.lastName}
            </Text>
            <Text style={styles.email} numberOfLines={1}>
              {user.email}
            </Text>
            <View style={styles.pillRow}>
              {meta && (
                <View style={[styles.pill, { backgroundColor: meta.bg }]}>
                  <Ionicons name={meta.icon} size={12} color={meta.color} />
                  <Text style={[styles.pillText, { color: meta.color }]}>{meta.label}</Text>
                </View>
              )}
              {plan && (
                <View style={[styles.pill, { backgroundColor: colors.primaryBg }]}>
                  <Ionicons name="ribbon" size={12} color={colors.primaryDark} />
                  <Text style={[styles.pillText, { color: colors.primaryDark }]}>{plan}</Text>
                </View>
              )}
            </View>
          </View>
        </View>

        {/* Admin asked for documents to be re-uploaded — highest priority. */}
        {isTutor && profile.data?.needsResubmission && (
          <Pressable style={styles.resubmitCard} onPress={() => router.push('/dashboard/resubmit')}>
            <Ionicons name="alert-circle" size={22} color={colors.danger} />
            <View style={{ flex: 1 }}>
              <Text style={styles.resubmitTitle}>Action needed: re-upload documents</Text>
              <Text style={styles.resubmitText} numberOfLines={2}>
                {profile.data.resubmissionMessage || 'An admin asked you to re-submit some documents.'}
              </Text>
            </View>
            <Ionicons name="chevron-forward" size={18} color={colors.danger} />
          </Pressable>
        )}

        {/* Pending tutors: show exactly what's left, then "waiting for approval". */}
        {isTutor && !isApproved && !profile.data?.needsResubmission && profile.data?.completion && (
          <CompletionCard completion={profile.data.completion} />
        )}

        {/* Performance */}
        {isTutor && isApproved && (
          <>
            <Text style={styles.sectionTitle}>Performance</Text>
            {analytics.isLoading ? (
              <View style={styles.statGrid}>
                <Skeleton width="47%" height={92} radius={16} />
                <Skeleton width="47%" height={92} radius={16} />
                <Skeleton width="47%" height={92} radius={16} />
                <Skeleton width="47%" height={92} radius={16} />
              </View>
            ) : (
              <View style={styles.statGrid}>
                <Stat icon="eye-outline" label="Profile views" value={analytics.data?.profileViews ?? 0} />
                <Stat icon="call-outline" label="Contact clicks" value={analytics.data?.contactClicks ?? 0} />
                <Stat icon="star-outline" label="Rating" value={analytics.data?.rating ?? 0} />
                <Stat icon="chatbox-outline" label="Reviews" value={analytics.data?.reviewCount ?? 0} />
              </View>
            )}
          </>
        )}

        {/* Manage */}
        {isTutor && (
          <>
            <Text style={styles.sectionTitle}>Manage your profile</Text>
            <View style={styles.menu}>
              <Row icon="mail-outline" label="Messages" sub="Enquiries from students and parents" href="/dashboard/enquiries" />
              <Row icon="person-circle-outline" label="Edit Profile" sub="Photo, bio, contact details" href="/dashboard/edit-profile" />
              <Row icon="book-outline" label="Subjects" sub="Curricula, levels and subjects you teach" href="/dashboard/subjects" />
              <Row icon="calendar-outline" label="Availability" sub="Days and times you are free" href="/dashboard/availability" />
              <Row icon="bar-chart-outline" label="Analytics" sub="Views, clicks and engagement" href="/dashboard/analytics" />
              <Row icon="card-outline" label="Subscription" sub="Plan, billing and renewal" href="/dashboard/subscription" />
              <Row icon="school-outline" label="University Portal" sub="Lecture requests and your university profile" href="/university/portal" />
              <Row icon="notifications-outline" label="Notifications" sub="Alerts and updates" href="/notifications" />
              <Row icon="lock-closed-outline" label="Change Password" sub="Update your account password" href="/dashboard/change-password" last />
            </View>
          </>
        )}

        {!isTutor && (
          <View style={styles.menu}>
            <Row icon="notifications-outline" label="Notifications" sub="Alerts and updates" href="/notifications" last />
          </View>
        )}

        <Pressable style={styles.logout} onPress={onLogout}>
          <Ionicons name="log-out-outline" size={18} color={colors.danger} />
          <Text style={styles.logoutText}>Log Out</Text>
        </Pressable>
      </ScrollView>
    </SafeAreaView>
  );
}

// Where each outstanding item gets fixed.
const ITEM_ROUTE: Record<string, Href> = {
  subjects: '/dashboard/subjects',
  availability: '/dashboard/availability',
};
const routeFor = (key: string): Href => ITEM_ROUTE[key] ?? '/dashboard/edit-profile';

function CompletionCard({ completion }: { completion: ProfileCompletion }) {
  const router = useRouter();
  const { percent, doneCount, totalCount, allSubmitted, items } = completion;
  const missing = items.filter((i) => !i.done);

  // Everything is in — nothing left for the tutor to do but wait.
  if (allSubmitted) {
    return (
      <View style={styles.doneCard}>
        <Ionicons name="checkmark-circle" size={22} color={colors.success} />
        <View style={{ flex: 1 }}>
          <Text style={styles.doneTitle}>All information submitted</Text>
          <Text style={styles.doneText}>
            Your profile and documents are complete. An admin is reviewing your account — you'll
            appear in search once approved.
          </Text>
        </View>
      </View>
    );
  }

  return (
    <View style={styles.progressCard}>
      <View style={styles.progressHead}>
        <Text style={styles.progressTitle}>Complete your profile</Text>
        <Text style={styles.progressPct}>{percent}%</Text>
      </View>

      <View style={styles.bar}>
        <View style={[styles.barFill, { width: `${percent}%` }]} />
      </View>
      <Text style={styles.progressSub}>
        {doneCount} of {totalCount} done · finish these to be sent for admin approval
      </Text>

      {missing.map((item) => (
        <Pressable key={item.key} style={styles.todoRow} onPress={() => router.push(routeFor(item.key))}>
          <Ionicons name="ellipse-outline" size={18} color={colors.textLight} />
          <Text style={styles.todoLabel}>{item.label}</Text>
          <Ionicons name="chevron-forward" size={16} color={colors.textLight} />
        </Pressable>
      ))}
    </View>
  );
}

function Stat({ icon, label, value }: { icon: keyof typeof Ionicons.glyphMap; label: string; value: number }) {
  return (
    <View style={styles.stat}>
      <View style={styles.statIcon}>
        <Ionicons name={icon} size={16} color={colors.primary} />
      </View>
      <Text style={styles.statValue}>{value}</Text>
      <Text style={styles.statLabel}>{label}</Text>
    </View>
  );
}

function Row({
  icon,
  label,
  sub,
  href,
  last,
}: {
  icon: keyof typeof Ionicons.glyphMap;
  label: string;
  sub?: string;
  href?: Href;
  last?: boolean;
}) {
  const router = useRouter();
  return (
    <Pressable
      style={[styles.row, last && { borderBottomWidth: 0 }]}
      onPress={() => href && router.push(href)}
      disabled={!href}
    >
      <View style={styles.rowIcon}>
        <Ionicons name={icon} size={18} color={colors.primary} />
      </View>
      <View style={{ flex: 1 }}>
        <Text style={styles.rowLabel}>{label}</Text>
        {sub && <Text style={styles.rowSub}>{sub}</Text>}
      </View>
      <Ionicons name="chevron-forward" size={18} color={colors.textLight} />
    </Pressable>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg },
  content: { padding: spacing.lg, paddingBottom: 100 },

  header: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  avatar: { width: 64, height: 64, borderRadius: 32, backgroundColor: colors.surface },
  name: { fontSize: 19, fontWeight: '800', color: colors.text },
  email: { fontSize: 13, color: colors.textMuted, marginTop: 1 },
  pillRow: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.xs, marginTop: spacing.sm },
  pill: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: radius.pill,
  },
  pillText: { fontSize: 11, fontWeight: '700' },

  resubmitCard: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    backgroundColor: '#FEE2E2',
    borderWidth: 1,
    borderColor: '#FCA5A5',
    borderRadius: radius.lg,
    padding: spacing.md,
    marginTop: spacing.lg,
  },
  resubmitTitle: { fontSize: 14, fontWeight: '800', color: '#991B1B' },
  resubmitText: { fontSize: 12, color: '#B91C1C', marginTop: 2, lineHeight: 16 },
  progressCard: {
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.lg,
    padding: spacing.md,
    marginTop: spacing.lg,
  },
  progressHead: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  progressTitle: { fontSize: 15, fontWeight: '800', color: colors.text },
  progressPct: { fontSize: 15, fontWeight: '800', color: colors.primary },
  bar: {
    height: 8,
    borderRadius: 4,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    overflow: 'hidden',
    marginTop: spacing.sm,
  },
  barFill: { height: '100%', backgroundColor: colors.primary },
  progressSub: { fontSize: 12, color: colors.textMuted, marginTop: 6 },
  todoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    paddingVertical: spacing.sm,
    borderTopWidth: 1,
    borderTopColor: colors.border,
    marginTop: spacing.sm,
  },
  todoLabel: { flex: 1, fontSize: 14, color: colors.text },

  doneCard: {
    flexDirection: 'row',
    gap: spacing.sm,
    backgroundColor: colors.successBg,
    borderRadius: radius.lg,
    padding: spacing.md,
    marginTop: spacing.lg,
  },
  doneTitle: { fontSize: 15, fontWeight: '800', color: '#166534' },
  doneText: { fontSize: 13, color: '#166534', lineHeight: 18, marginTop: 2 },

  sectionTitle: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.textLight,
    letterSpacing: 0.6,
    textTransform: 'uppercase',
    marginTop: spacing.xl,
    marginBottom: spacing.sm,
  },

  statGrid: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm },
  stat: {
    flexGrow: 1,
    flexBasis: '47%',
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.lg,
    padding: spacing.md,
  },
  statIcon: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  statValue: { fontSize: 24, fontWeight: '800', color: colors.text, marginTop: spacing.sm },
  statLabel: { fontSize: 12, color: colors.textMuted, marginTop: 1 },

  menu: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    overflow: 'hidden',
  },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    padding: spacing.md,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  rowIcon: {
    width: 34,
    height: 34,
    borderRadius: radius.sm,
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  rowLabel: { fontSize: 15, color: colors.text, fontWeight: '700' },
  rowSub: { fontSize: 12, color: colors.textMuted, marginTop: 1 },

  logout: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: spacing.sm,
    marginTop: spacing.xl,
    paddingVertical: spacing.md,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.pill,
  },
  logoutText: { color: colors.danger, fontWeight: '700', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
});
