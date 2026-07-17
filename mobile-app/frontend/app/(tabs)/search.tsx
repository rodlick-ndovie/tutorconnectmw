import { useState } from 'react';
import { View, Text, TextInput, FlatList, StyleSheet, Pressable, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useQuery } from '@tanstack/react-query';
import { tutorsApi, metaApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useInfiniteList } from '../../src/hooks/useInfiniteList';
import { TutorCard } from '../../src/components/TutorCard';
import { ErrorState } from '../../src/components/ErrorState';
import { ListFooter } from '../../src/components/ListFooter';
import { SkeletonList } from '../../src/components/skeletons';
import { FilterSheet, activeFilterCount } from '../../src/components/FilterSheet';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { TutorFilters } from '../../src/types';

export default function Search() {
  const [q, setQ] = useState('');
  const [filters, setFilters] = useState<TutorFilters>({ sort: 'rating' });
  const [sheetOpen, setSheetOpen] = useState(false);

  const districts = useQuery({ queryKey: ['meta', 'districts'], queryFn: metaApi.districts });
  const curricula = useQuery({ queryKey: ['meta', 'curricula'], queryFn: metaApi.curricula });
  // Levels/subjects cascade off the curriculum being edited, so the sheet owns
  // those queries — from here we'd only ever see the already-applied filters.

  const results = useInfiniteList(
    ['tutors', 'search', { ...filters, q }],
    (page) => tutorsApi.search({ ...filters, q: q || undefined }, page, 20),
    { keepPrevious: true }
  );

  const count = activeFilterCount(filters);
  const { refreshing, onRefresh } = useRefresh(results.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.topRow}>
        <View style={styles.searchBar}>
          <Ionicons name="search" size={20} color={colors.textLight} />
          <TextInput
            style={styles.input}
            placeholder="Search tutors by name"
            placeholderTextColor={colors.textLight}
            value={q}
            onChangeText={setQ}
            returnKeyType="search"
          />
          {q.length > 0 && (
            <Pressable onPress={() => setQ('')} hitSlop={8}>
              <Ionicons name="close-circle" size={18} color={colors.textLight} />
            </Pressable>
          )}

          {/* Filter lives inside the input, after a divider. */}
          <View style={styles.divider} />
          <Pressable style={styles.filterBtn} onPress={() => setSheetOpen(true)} hitSlop={8}>
            <Ionicons
              name="options-outline"
              size={20}
              color={count > 0 ? colors.primary : colors.textMuted}
            />
            {count > 0 && (
              <View style={styles.badge}>
                <Text style={styles.badgeText}>{count}</Text>
              </View>
            )}
          </Pressable>
        </View>
      </View>

      {/* What's actually applied, and a one-tap way to drop each one. Without
          this the only clue was a number on the filter icon. */}
      {count > 0 && (
        <View style={styles.activeRow}>
          {(['curriculum', 'level', 'subject', 'district', 'teaching_mode'] as const)
            .filter((k) => filters[k])
            .map((k) => (
              <Pressable
                key={k}
                style={styles.activeChip}
                onPress={() =>
                  setFilters((f) => {
                    const next = { ...f, [k]: undefined };
                    // Dropping a parent must drop its children too, or we'd
                    // search for a level/subject with no curriculum behind it.
                    if (k === 'curriculum') { next.level = undefined; next.subject = undefined; }
                    if (k === 'level') next.subject = undefined;
                    return next;
                  })
                }
              >
                <Text style={styles.activeChipText} numberOfLines={1}>{filters[k]}</Text>
                <Ionicons name="close" size={14} color={colors.primaryDark} />
              </Pressable>
            ))}
          <Pressable style={styles.clearAll} onPress={() => setFilters({ sort: filters.sort })}>
            <Text style={styles.clearAllText}>Clear all</Text>
          </Pressable>
        </View>
      )}

      {results.isLoading ? (
        <View style={styles.list}>
          <SkeletonList count={6} />
        </View>
      ) : results.isError ? (
        <ErrorState message={(results.error as Error)?.message} onRetry={() => results.refetch()} />
      ) : (
        <FlatList
          data={results.items}
          keyExtractor={(t) => String(t.id)}
          contentContainerStyle={styles.list}
          showsVerticalScrollIndicator={false}
          onEndReached={results.loadMore}
          onEndReachedThreshold={0.5}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
          ListHeaderComponent={
            <Text style={styles.count}>
              {results.total} tutor{results.total === 1 ? '' : 's'} found
            </Text>
          }
          renderItem={({ item }) => <TutorCard tutor={item} />}
          ListEmptyComponent={<Text style={styles.empty}>No tutors match your filters.</Text>}
          ListFooterComponent={
            <ListFooter loadingMore={results.loadingMore} hasMore={results.hasMore} count={results.items.length} />
          }
        />
      )}

      <FilterSheet
        visible={sheetOpen}
        filters={filters}
        districts={districts.data ?? []}
        curricula={curricula.data ?? []}
        onApply={setFilters}
        onClose={() => setSheetOpen(false)}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  topRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    paddingHorizontal: spacing.lg,
    paddingTop: spacing.sm,
    paddingBottom: spacing.md,
  },
  searchBar: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    backgroundColor: colors.surface,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    borderWidth: 1,
    borderColor: colors.border,
  },
  input: { flex: 1, fontSize: 15, color: colors.text },
  divider: { width: 1, height: 22, backgroundColor: colors.border, marginHorizontal: spacing.xs },
  filterBtn: { alignItems: 'center', justifyContent: 'center', paddingLeft: 2 },
  badge: {
    position: 'absolute',
    top: -6,
    right: -8,
    minWidth: 16,
    height: 16,
    borderRadius: 8,
    paddingHorizontal: 4,
    backgroundColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
  },
  badgeText: { color: colors.white, fontSize: 10, fontWeight: '800' },
  activeRow: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    alignItems: 'center',
    gap: spacing.sm,
    paddingHorizontal: spacing.lg,
    paddingBottom: spacing.md,
  },
  activeChip: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    maxWidth: '60%',
    paddingLeft: spacing.md,
    paddingRight: spacing.sm,
    paddingVertical: 6,
    borderRadius: radius.pill,
    backgroundColor: colors.primaryBg,
    borderWidth: 1,
    borderColor: colors.primary,
  },
  activeChipText: { flexShrink: 1, fontSize: 12, fontWeight: '700', color: colors.primaryDark },
  clearAll: { paddingVertical: 6, paddingHorizontal: spacing.sm },
  clearAllText: { fontSize: 12, fontWeight: '700', color: colors.textMuted },
  list: { paddingHorizontal: spacing.lg, paddingBottom: 100, gap: spacing.md },
  count: { color: colors.textMuted, fontWeight: '600', marginBottom: spacing.sm },
  empty: { color: colors.textMuted, textAlign: 'center', marginTop: spacing.xxl },
});
