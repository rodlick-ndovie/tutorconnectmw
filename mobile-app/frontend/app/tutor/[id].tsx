import { useState } from 'react';
import { ScrollView, View, Text, StyleSheet, Pressable, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { useLocalSearchParams, Stack } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { tutorsApi } from '../../src/api/endpoints';
import { RatingStars } from '../../src/components/RatingStars';
import { ContactSheet } from '../../src/components/ContactSheet';
import { ReviewSheet } from '../../src/components/ReviewSheet';
import { useFavorites } from '../../src/store/favorites';
import { useRefresh } from '../../src/hooks/useRefresh';
import { Avatar } from '../../src/components/Avatar';
import { ProfileSkeleton } from '../../src/components/skeletons';
import { colors, gradients, radius, spacing } from '../../src/theme/colors';

export default function TutorDetail() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const tutorId = Number(id);
  const [contactOpen, setContactOpen] = useState(false);
  const [reviewOpen, setReviewOpen] = useState(false);

  const isFavorite = useFavorites((s) => s.isFavorite(tutorId));
  const toggle = useFavorites((s) => s.toggle);

  const tutor = useQuery({ queryKey: ['tutor', tutorId], queryFn: () => tutorsApi.get(tutorId) });
  const reviews = useQuery({
    queryKey: ['tutor', tutorId, 'reviews'],
    queryFn: () => tutorsApi.reviews(tutorId),
  });

  const { refreshing, onRefresh } = useRefresh(tutor.refetch, reviews.refetch);

  if (tutor.isLoading) return <ProfileSkeleton />;
  if (tutor.isError || !tutor.data) {
    return (
      <View style={styles.center}>
        <Text style={styles.error}>{(tutor.error as Error)?.message ?? 'Tutor unavailable'}</Text>
      </View>
    );
  }

  const t = tutor.data;
  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: t.name }} />
      <ScrollView
        contentContainerStyle={styles.scroll}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
      >
        {/* Cover banner — falls back to the brand gradient when the tutor has none. */}
        <View style={styles.coverWrap}>
          {t.coverPhoto ? (
            <Image source={{ uri: t.coverPhoto }} style={styles.cover} contentFit="cover" transition={200} />
          ) : (
            <LinearGradient colors={[...gradients.hero]} style={styles.cover} />
          )}
          <Pressable style={styles.favFloat} hitSlop={8} onPress={() => toggle(t)}>
            <Ionicons
              name={isFavorite ? 'heart' : 'heart-outline'}
              size={22}
              color={isFavorite ? colors.danger : colors.text}
            />
          </Pressable>
        </View>

        <View style={styles.body}>
          <View style={styles.header}>
            <Avatar uri={t.profilePicture} name={t.name} size={84} style={styles.avatar} />
            <View style={{ flex: 1, paddingTop: spacing.sm }}>
              <Text style={styles.name}>{t.name}</Text>
              <RatingStars rating={t.rating} reviewCount={t.reviewCount} />
              {t.district && <Text style={styles.meta}>{t.location || t.district}</Text>}
            </View>
          </View>

        <View style={styles.statRow}>
          <Stat label="Experience" value={t.experienceYears ? `${t.experienceYears} yrs` : '—'} />
          <Stat label="Mode" value={t.teachingMode || '—'} />
          {/* Rating instead of the subscription plan — the plan tier is private. */}
          <Stat label="Rating" value={t.rating ? `${t.rating.toFixed(1)}★` : 'New'} />
        </View>

        {t.bio && (
          <Section title="About">
            <Text style={styles.bio}>{t.bio}</Text>
          </Section>
        )}

        {Object.keys(t.structuredSubjects || {}).length > 0 && (
          <Section title="Subjects">
            {Object.entries(t.structuredSubjects).map(([curriculum, data]) => (
              <View key={curriculum} style={{ marginBottom: spacing.sm }}>
                <Text style={styles.curriculum}>{curriculum}</Text>
                {Object.entries(data.levels || {}).map(([level, subjects]) => (
                  <View key={level} style={{ marginTop: 4 }}>
                    <Text style={styles.level}>{level}</Text>
                    <View style={styles.chips}>
                      {subjects.map((s) => (
                        <View key={s} style={styles.chip}>
                          <Text style={styles.chipText}>{s}</Text>
                        </View>
                      ))}
                    </View>
                  </View>
                ))}
              </View>
            ))}
          </Section>
        )}

        {t.availability?.days && t.availability.days.length > 0 && (
          <Section title="Availability">
            <Text style={styles.bio}>
              {t.availability.days.join(', ')}
              {t.availability.times?.length ? `\n${t.availability.times.join(', ')}` : ''}
            </Text>
          </Section>
        )}

        <View style={styles.reviewHeader}>
          <Text style={styles.sectionTitle}>Reviews ({t.reviewCount})</Text>
          <Pressable onPress={() => setReviewOpen(true)}>
            <Text style={styles.writeReview}>Write a review</Text>
          </Pressable>
        </View>
        {reviews.data?.length ? (
          reviews.data.map((r) => (
            <View key={r.id} style={styles.review}>
              <View style={styles.reviewRow}>
                <Text style={styles.reviewer}>{r.reviewerName}</Text>
                <RatingStars rating={r.rating} />
              </View>
              {r.comment && <Text style={styles.reviewBody}>{r.comment}</Text>}
            </View>
          ))
        ) : (
          <Text style={styles.meta}>No reviews yet. Be the first!</Text>
        )}
        </View>
      </ScrollView>

      <View style={styles.footer}>
        <Pressable style={styles.contactBtn} onPress={() => setContactOpen(true)}>
          <Ionicons name="chatbubbles" size={20} color={colors.white} />
          <Text style={styles.contactText}>Contact Tutor</Text>
        </Pressable>
      </View>

      <ContactSheet tutor={t} visible={contactOpen} onClose={() => setContactOpen(false)} />
      <ReviewSheet tutorId={tutorId} visible={reviewOpen} onClose={() => setReviewOpen(false)} />
    </SafeAreaView>
  );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <View style={styles.section}>
      <Text style={styles.sectionTitle}>{title}</Text>
      {children}
    </View>
  );
}

function Stat({ label, value }: { label: string; value: string }) {
  return (
    <View style={styles.stat}>
      <Text style={styles.statValue}>{value}</Text>
      <Text style={styles.statLabel}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg },
  error: { color: colors.danger },
  scroll: { paddingBottom: 100 },
  body: { paddingHorizontal: spacing.lg },
  coverWrap: { width: '100%', height: 170, backgroundColor: colors.surface },
  cover: { width: '100%', height: '100%' },
  favFloat: {
    position: 'absolute',
    top: spacing.md,
    right: spacing.md,
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: 'rgba(255,255,255,0.92)',
    alignItems: 'center',
    justifyContent: 'center',
  },
  header: { flexDirection: 'row', alignItems: 'flex-start', gap: spacing.md },
  avatar: {
    width: 84,
    height: 84,
    borderRadius: 42,
    backgroundColor: colors.surface,
    borderWidth: 3,
    borderColor: colors.white,
    marginTop: -34, // overlap the cover
  },
  name: { fontSize: 22, fontWeight: '800', color: colors.text },
  meta: { color: colors.textMuted, fontSize: 13, marginTop: 2 },
  statRow: {
    flexDirection: 'row',
    backgroundColor: colors.surface,
    borderRadius: radius.lg,
    padding: spacing.md,
    marginTop: spacing.lg,
  },
  stat: { flex: 1, alignItems: 'center' },
  statValue: { fontSize: 15, fontWeight: '700', color: colors.text },
  statLabel: { fontSize: 12, color: colors.textMuted, marginTop: 2 },
  section: { marginTop: spacing.xl },
  sectionTitle: { fontSize: 17, fontWeight: '700', color: colors.text, marginBottom: spacing.sm },
  bio: { fontSize: 15, color: colors.textMuted, lineHeight: 22 },
  curriculum: { fontSize: 15, fontWeight: '700', color: colors.primary },
  level: { fontSize: 13, fontWeight: '600', color: colors.text, marginTop: 4 },
  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.xs, marginTop: 4 },
  chip: {
    backgroundColor: colors.surface,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: radius.pill,
    borderWidth: 1,
    borderColor: colors.border,
  },
  chipText: { fontSize: 12, color: colors.textMuted },
  reviewHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: spacing.xl,
  },
  writeReview: { color: colors.accent, fontWeight: '600', fontSize: 14 },
  review: { borderTopWidth: 1, borderTopColor: colors.border, paddingVertical: spacing.md },
  reviewRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  reviewer: { fontWeight: '700', color: colors.text },
  reviewBody: { color: colors.textMuted, marginTop: 4, lineHeight: 20 },
  footer: {
    padding: spacing.lg,
    borderTopWidth: 1,
    borderTopColor: colors.border,
    backgroundColor: colors.white,
  },
  contactBtn: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
  },
  contactText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
