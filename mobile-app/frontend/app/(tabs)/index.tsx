import { ScrollView, View, Text, StyleSheet, Pressable, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Avatar } from '../../src/components/Avatar';
import { useQuery, type UseQueryResult } from '@tanstack/react-query';
import { useRouter } from 'expo-router';
import { tutorsApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useAuth } from '../../src/store/auth';
import { TutorCard } from '../../src/components/TutorCard';
import { TutorCardSkeleton } from '../../src/components/skeletons';
import { SectionHeader } from '../../src/components/SectionHeader';
import { ErrorState } from '../../src/components/ErrorState';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { TutorCard as TutorCardType } from '../../src/types';

const SECTION_SIZE = 5;

// Landing-page entry points. Every kind of teacher/service is reachable from
// here, so nothing is buried. All tiles use the brand colour — one visual
// language, no rainbow.
const QUICK_ACTIONS: {
  title: string;
  icon: keyof typeof Ionicons.glyphMap;
  href: string;
}[] = [
  { title: 'Find Tutors', icon: 'search', href: '/search' },
  { title: 'University Tutors', icon: 'school', href: '/university' },
  { title: 'Companies', icon: 'business', href: '/university?type=firm' },
  { title: 'Request a Tutor', icon: 'people', href: '/parent-requests' },
  { title: 'Request a Lecture', icon: 'easel', href: '/university/request-lecture' },
  { title: 'Resources', icon: 'library', href: '/resources' },
  { title: 'Notices', icon: 'megaphone', href: '/notices' },
  { title: 'Join as Tutor', icon: 'person-add', href: '/register' },
];

export default function Home() {
  const router = useRouter();
  const user = useAuth((s) => s.user);

  const topRated = useQuery({
    queryKey: ['tutors', 'top-rated'],
    queryFn: () => tutorsApi.search({ sort: 'rating' }, 1, SECTION_SIZE),
  });
  const experienced = useQuery({
    queryKey: ['tutors', 'experienced'],
    queryFn: () => tutorsApi.search({ sort: 'experience' }, 1, SECTION_SIZE),
  });

  const { refreshing, onRefresh } = useRefresh(topRated.refetch, experienced.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <ScrollView
        contentContainerStyle={styles.content}
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
      >
        <View style={styles.headerRow}>
          <Text style={styles.greeting}>Hi {user?.firstName ?? 'there'}</Text>
          <Pressable onPress={() => router.push('/profile')}>
            <Avatar uri={user?.profilePicture} name={user?.firstName ?? 'U'} size={40} />
          </Pressable>
        </View>

        <Text style={styles.title}>Easily find the perfect tutor for any subject</Text>

        <Pressable style={styles.search} onPress={() => router.push('/search')}>
          <Ionicons name="search" size={20} color={colors.textLight} />
          <Text style={styles.searchPlaceholder}>Search tutors</Text>
          <Ionicons name="options-outline" size={20} color={colors.primary} />
        </Pressable>

        {/* Quick actions — the main entry points into the app. */}
        <View style={styles.actions}>
          {QUICK_ACTIONS.map((a) => (
            <Pressable key={a.title} style={styles.action} onPress={() => router.push(a.href as never)}>
              <View style={styles.actionIcon}>
                <Ionicons name={a.icon} size={26} color={colors.primary} />
              </View>
              <Text style={styles.actionTitle} numberOfLines={2}>
                {a.title}
              </Text>
            </Pressable>
          ))}
        </View>

        <TutorSection
          title="Top Rated Tutors"
          query={topRated}
          onSeeAll={() => router.push('/search')}
          emptyLabel="No tutors available right now."
        />

        <TutorSection
          title="Most Experienced"
          query={experienced}
          onSeeAll={() => router.push('/search')}
          emptyLabel="No tutors available right now."
        />
      </ScrollView>
    </SafeAreaView>
  );
}

/** A titled section rendering tutors as a vertical list (one card per row). */
function TutorSection({
  title,
  query,
  onSeeAll,
  emptyLabel,
}: {
  title: string;
  query: UseQueryResult<{ items: TutorCardType[]; meta: unknown }>;
  onSeeAll: () => void;
  emptyLabel: string;
}) {
  return (
    <>
      <SectionHeader title={title} onSeeAll={onSeeAll} />
      {query.isLoading ? (
        <View style={styles.list}>
          <TutorCardSkeleton />
          <TutorCardSkeleton />
        </View>
      ) : query.isError ? (
        <ErrorState compact message={(query.error as Error)?.message} onRetry={() => query.refetch()} />
      ) : query.data && query.data.items.length > 0 ? (
        <View style={styles.list}>
          {query.data.items.map((tutor) => (
            <TutorCard key={tutor.id} tutor={tutor} />
          ))}
        </View>
      ) : (
        <Text style={styles.empty}>{emptyLabel}</Text>
      )}
    </>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  // Bottom padding must clear the 60px tab bar, or the last cards sit under it
  // and can never be scrolled into view.
  content: { padding: spacing.lg, paddingBottom: 100 },
  headerRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  greeting: { fontSize: 16, color: colors.textMuted, fontWeight: '600' },
  avatar: { width: 40, height: 40, borderRadius: 20, backgroundColor: colors.surface },
  title: { fontSize: 26, fontWeight: '800', color: colors.text, marginTop: spacing.md, lineHeight: 32 },
  search: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    backgroundColor: colors.surface,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    marginTop: spacing.lg,
    borderWidth: 1,
    borderColor: colors.border,
  },
  searchPlaceholder: { flex: 1, color: colors.textLight, fontSize: 15 },
  actions: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginTop: spacing.lg,
    rowGap: spacing.lg,
  },
  action: { width: '25%', alignItems: 'center', gap: 6, paddingHorizontal: 2 },
  // Plain icons — no background card behind them.
  actionIcon: { height: 34, justifyContent: 'center', alignItems: 'center' },
  actionTitle: {
    fontSize: 12,
    fontWeight: '600',
    color: colors.text,
    textAlign: 'center',
    lineHeight: 15,
  },
  list: { gap: spacing.md },
  empty: { color: colors.textMuted, marginVertical: spacing.lg },
});
