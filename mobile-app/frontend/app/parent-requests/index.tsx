import { View, Text, FlatList, StyleSheet, Pressable, ActivityIndicator, RefreshControl, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { parentRequestsApi } from '../../src/api/endpoints';
import { ErrorState } from '../../src/components/ErrorState';
import { ListFooter } from '../../src/components/ListFooter';
import { SkeletonList, ListRowSkeleton } from '../../src/components/skeletons';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useInfiniteList } from '../../src/hooks/useInfiniteList';
import { useAuth } from '../../src/store/auth';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { ParentRequest } from '../../src/types';

const budget = (r: ParentRequest) => {
  if (r.budgetMin == null && r.budgetMax == null) return null;
  const parts = [r.budgetMin, r.budgetMax].filter((n) => n != null) as number[];
  const range = parts.map((n) => `MWK ${n.toLocaleString()}`).join(' – ');
  return r.budgetPeriod ? `${range} / ${r.budgetPeriod}` : range;
};

export default function ParentRequests() {
  const router = useRouter();
  const qc = useQueryClient();
  const user = useAuth((s) => s.user);
  // Only an approved tutor can apply to a parent's request.
  const canApply = user?.role === 'trainer' && user?.tutorStatus === 'approved';

  const q = useInfiniteList(['parent-requests'], (page) => parentRequestsApi.list({ page }));
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  const apply = useMutation({
    mutationFn: (id: number) => parentRequestsApi.apply(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['parent-requests'] });
      Alert.alert('Applied', 'The parent will be notified that you can help. They will contact you.');
    },
    onError: (e) => Alert.alert('Could not apply', (e as Error).message),
  });

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Request a Tutor', headerShown: true }} />

      <View style={styles.intro}>
        <Text style={styles.introText}>
          Post what you need and let tutors come to you — or browse what other parents are asking for.
        </Text>
      </View>

      {q.isLoading ? (
        <View style={styles.list}>
          <SkeletonList count={6} Item={ListRowSkeleton} gap={spacing.md} />
        </View>
      ) : q.isError ? (
        <ErrorState message={(q.error as Error)?.message} onRetry={() => q.refetch()} />
      ) : (
        <FlatList
          data={q.items}
          keyExtractor={(r) => String(r.id)}
          contentContainerStyle={styles.list}
          onEndReached={q.loadMore}
          onEndReachedThreshold={0.5}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
          ListFooterComponent={
            <ListFooter loadingMore={q.loadingMore} hasMore={q.hasMore} count={q.items.length} />
          }
          renderItem={({ item }) => {
            const b = budget(item);
            return (
              <View style={styles.card}>
                <View style={styles.head}>
                  <View style={styles.iconWrap}>
                    <Ionicons name="people" size={18} color={colors.primary} />
                  </View>
                  <View style={{ flex: 1 }}>
                    <Text style={styles.title} numberOfLines={1}>
                      {item.gradeClass} · {item.curriculum}
                    </Text>
                    <Text style={styles.meta} numberOfLines={1}>
                      {[item.district, item.specificLocation, item.mode].filter(Boolean).join(' · ')}
                    </Text>
                  </View>
                </View>

                {item.subjects.length > 0 && (
                  <View style={styles.chips}>
                    {item.subjects.slice(0, 5).map((s) => (
                      <View key={s} style={styles.chip}>
                        <Text style={styles.chipText}>{s}</Text>
                      </View>
                    ))}
                  </View>
                )}

                {b && <Text style={styles.budget}>{b}</Text>}
                {item.notes && (
                  <Text style={styles.notes} numberOfLines={2}>
                    {item.notes}
                  </Text>
                )}
                <View style={styles.cardFoot}>
                  <Text style={styles.ref}>Ref: {item.referenceCode}</Text>
                  {canApply && (
                    <Pressable
                      style={[styles.applyBtn, apply.isPending && apply.variables === item.id && { opacity: 0.6 }]}
                      onPress={() => apply.mutate(item.id)}
                      disabled={apply.isPending && apply.variables === item.id}
                    >
                      {apply.isPending && apply.variables === item.id ? (
                        <ActivityIndicator size="small" color={colors.white} />
                      ) : (
                        <Text style={styles.applyText}>Apply</Text>
                      )}
                    </Pressable>
                  )}
                </View>
              </View>
            );
          }}
          ListEmptyComponent={
            <View style={styles.empty}>
              <Ionicons name="people-outline" size={44} color={colors.textLight} />
              <Text style={styles.emptyTitle}>No open requests</Text>
              <Text style={styles.emptyHint}>Be the first to post what you're looking for.</Text>
            </View>
          }
        />
      )}

      <View style={styles.footer}>
        <Pressable style={styles.postBtn} onPress={() => router.push('/parent-requests/new')}>
          <Ionicons name="add" size={18} color={colors.white} />
          <Text style={styles.postText}>Post a Request</Text>
        </Pressable>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  intro: { paddingHorizontal: spacing.lg, paddingTop: spacing.md },
  introText: { fontSize: 13, color: colors.textMuted, lineHeight: 19 },
  list: { padding: spacing.lg, gap: spacing.md, paddingBottom: spacing.xxl },
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
    gap: spacing.sm,
  },
  head: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  iconWrap: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  title: { fontSize: 15, fontWeight: '700', color: colors.text },
  meta: { fontSize: 12, color: colors.textMuted, marginTop: 1 },
  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.xs },
  chip: {
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: radius.pill,
  },
  chipText: { fontSize: 11, color: colors.textMuted, fontWeight: '600' },
  budget: { fontSize: 13, fontWeight: '700', color: colors.primaryDark },
  notes: { fontSize: 13, color: colors.textMuted, lineHeight: 18 },
  cardFoot: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', marginTop: 2 },
  ref: { fontSize: 10, color: colors.textLight, flex: 1 },
  applyBtn: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.sm,
    minWidth: 74,
    alignItems: 'center',
  },
  applyText: { color: colors.white, fontWeight: '800', fontSize: 13, textAlign: 'center' },
  empty: { alignItems: 'center', marginTop: spacing.xxl * 2, gap: spacing.sm },
  emptyTitle: { fontSize: 16, fontWeight: '700', color: colors.text },
  emptyHint: { fontSize: 13, color: colors.textMuted },
  footer: {
    padding: spacing.lg,
    borderTopWidth: 1,
    borderTopColor: colors.border,
    backgroundColor: colors.white,
  },
  postBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
  },
  postText: { color: colors.white, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
});
