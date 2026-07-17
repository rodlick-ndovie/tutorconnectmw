import { useEffect, useState } from 'react';
import { Modal, View, Text, StyleSheet, Pressable, ScrollView, ActivityIndicator } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useQuery } from '@tanstack/react-query';
import { Select } from './Select';
import { metaApi } from '../api/endpoints';
import { colors, radius, spacing } from '../theme/colors';
import type { TutorFilters } from '../types';

const SORTS: { key: NonNullable<TutorFilters['sort']>; label: string }[] = [
  { key: 'rating', label: 'Top rated' },
  { key: 'experience', label: 'Most experienced' },
  { key: 'reviews', label: 'Most reviewed' },
];

const MODES = [
  { key: 'online', label: 'Online' },
  { key: 'in-person', label: 'In person' },
  { key: 'both', label: 'Both' },
];

/** Count of user-chosen filters (sort is always set, so it isn't counted). */
export function activeFilterCount(f: TutorFilters): number {
  return [f.district, f.curriculum, f.level, f.subject, f.teaching_mode].filter(Boolean).length;
}

export function FilterSheet({
  visible,
  filters,
  districts,
  curricula,
  onApply,
  onClose,
}: {
  visible: boolean;
  filters: TutorFilters;
  districts: string[];
  curricula: string[];
  onApply: (f: TutorFilters) => void;
  onClose: () => void;
}) {
  const [draft, setDraft] = useState<TutorFilters>(filters);

  // Re-seed the draft each time the sheet opens.
  useEffect(() => {
    if (visible) setDraft(filters);
  }, [visible, filters]);

  // Levels and subjects cascade off the DRAFT, not the applied filters. Keying
  // them off the applied filters (as this used to) meant picking a curriculum in
  // the sheet changed nothing until you hit Apply — so every level in every
  // curriculum stayed on screen.
  const levels = useQuery({
    queryKey: ['meta', 'levels', draft.curriculum],
    queryFn: () => metaApi.levels(draft.curriculum),
    enabled: visible && !!draft.curriculum,
  });
  const subjects = useQuery({
    queryKey: ['meta', 'subjects', draft.curriculum, draft.level ?? null],
    queryFn: () => metaApi.subjects(draft.curriculum, draft.level),
    enabled: visible && !!draft.curriculum,
  });

  // Tapping the selected value again clears it (except sort, which is required).
  const toggle = <K extends keyof TutorFilters>(key: K, value: TutorFilters[K]) =>
    setDraft((d) => ({ ...d, [key]: d[key] === value ? undefined : value }));

  // Changing a parent invalidates its children: a level from the old curriculum
  // (or a subject from the old level) would filter for a combination that
  // cannot exist, and the search would silently return nothing.
  const pickCurriculum = (c: string) =>
    setDraft((d) => ({
      ...d,
      curriculum: d.curriculum === c ? undefined : c,
      level: undefined,
      subject: undefined,
    }));

  const pickLevel = (l: string) =>
    setDraft((d) => ({ ...d, level: d.level === l ? undefined : l, subject: undefined }));

  const clearAll = () => setDraft({ sort: draft.sort ?? 'rating' });
  const count = activeFilterCount(draft);

  return (
    <Modal visible={visible} animationType="slide" transparent onRequestClose={onClose}>
      <Pressable style={styles.backdrop} onPress={onClose} />
      <SafeAreaView style={styles.sheet} edges={['bottom']}>
        <View style={styles.handle} />

        <View style={styles.header}>
          <Text style={styles.title}>Filters</Text>
          <Pressable onPress={onClose} hitSlop={10}>
            <Ionicons name="close" size={22} color={colors.textMuted} />
          </Pressable>
        </View>

        <ScrollView contentContainerStyle={styles.body} showsVerticalScrollIndicator={false}>
          <Group title="Sort by">
            {SORTS.map((s) => (
              <Chip
                key={s.key}
                label={s.label}
                active={draft.sort === s.key}
                onPress={() => setDraft((d) => ({ ...d, sort: s.key }))}
              />
            ))}
          </Group>

          <Group title="Teaching mode">
            {MODES.map((m) => (
              <Chip
                key={m.key}
                label={m.label}
                active={draft.teaching_mode === m.key}
                onPress={() => toggle('teaching_mode', m.key)}
              />
            ))}
          </Group>

          {curricula.length > 0 && (
            <Group title="Curriculum">
              {curricula.map((c) => (
                <Chip key={c} label={c} active={draft.curriculum === c} onPress={() => pickCurriculum(c)} />
              ))}
            </Group>
          )}

          {/* Level and Subject only exist inside a curriculum, so they stay
              locked until one is chosen rather than listing every level of
              every curriculum at once. */}
          <View style={styles.group}>
            <Text style={styles.groupTitle}>Level</Text>
            {!draft.curriculum ? (
              <Text style={styles.lockedHint}>Choose a curriculum first</Text>
            ) : levels.isLoading ? (
              <ActivityIndicator color={colors.primary} style={styles.inlineLoader} />
            ) : (levels.data?.length ?? 0) === 0 ? (
              <Text style={styles.lockedHint}>No levels for {draft.curriculum}</Text>
            ) : (
              <View style={styles.chips}>
                {levels.data!.map((l) => (
                  <Chip key={l} label={l} active={draft.level === l} onPress={() => pickLevel(l)} />
                ))}
              </View>
            )}
          </View>

          <View style={styles.group}>
            <Text style={styles.groupTitle}>Subject</Text>
            {!draft.curriculum ? (
              <Text style={styles.lockedHint}>Choose a curriculum first</Text>
            ) : subjects.isLoading ? (
              <ActivityIndicator color={colors.primary} style={styles.inlineLoader} />
            ) : (
              // 20+ subjects per curriculum is far too many for a pill grid.
              <Select
                value={draft.subject}
                options={subjects.data ?? []}
                placeholder={draft.level ? `Any ${draft.level} subject` : 'Any subject'}
                onChange={(v) => setDraft((d) => ({ ...d, subject: v }))}
              />
            )}
          </View>

          {districts.length > 0 && (
            <View style={styles.group}>
              <Text style={styles.groupTitle}>District</Text>
              {/* 28 districts is too many for a pill grid — use a searchable dropdown. */}
              <Select
                value={draft.district}
                options={districts}
                placeholder="Any district"
                onChange={(v) => setDraft((d) => ({ ...d, district: v }))}
              />
            </View>
          )}
        </ScrollView>

        <View style={styles.footer}>
          <Pressable style={styles.clearBtn} onPress={clearAll}>
            <Text style={styles.clearText}>Clear all</Text>
          </Pressable>
          <Pressable
            style={styles.applyBtn}
            onPress={() => {
              onApply(draft);
              onClose();
            }}
          >
            <Text style={styles.applyText}>
              {count > 0 ? `Show results (${count})` : 'Show results'}
            </Text>
          </Pressable>
        </View>
      </SafeAreaView>
    </Modal>
  );
}

function Group({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <View style={styles.group}>
      <Text style={styles.groupTitle}>{title}</Text>
      <View style={styles.chips}>{children}</View>
    </View>
  );
}

function Chip({ label, active, onPress }: { label: string; active: boolean; onPress: () => void }) {
  return (
    <Pressable style={[styles.chip, active && styles.chipActive]} onPress={onPress}>
      <Text style={[styles.chipText, active && styles.chipTextActive]}>{label}</Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  backdrop: { flex: 1, backgroundColor: 'rgba(0,0,0,0.45)' },
  sheet: {
    backgroundColor: colors.white,
    borderTopLeftRadius: radius.xl,
    borderTopRightRadius: radius.xl,
    maxHeight: '85%',
  },
  handle: {
    width: 40,
    height: 4,
    borderRadius: 2,
    backgroundColor: colors.border,
    alignSelf: 'center',
    marginTop: spacing.sm,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
  },
  title: { fontSize: 18, fontWeight: '800', color: colors.text },
  body: { paddingHorizontal: spacing.lg, paddingBottom: spacing.lg },
  group: { marginBottom: spacing.lg },
  groupTitle: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.textLight,
    letterSpacing: 0.6,
    textTransform: 'uppercase',
    marginBottom: spacing.sm,
  },
  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm },
  lockedHint: { fontSize: 13, color: colors.textLight, fontStyle: 'italic' },
  inlineLoader: { alignSelf: 'flex-start' },
  chip: {
    paddingHorizontal: spacing.md,
    paddingVertical: 8,
    borderRadius: radius.pill,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
  },
  chipActive: { backgroundColor: colors.primary, borderColor: colors.primary },
  chipText: { fontSize: 13, color: colors.textMuted, fontWeight: '600' },
  chipTextActive: { color: colors.white },
  footer: {
    flexDirection: 'row',
    gap: spacing.md,
    paddingHorizontal: spacing.lg,
    paddingTop: spacing.md,
    paddingBottom: spacing.sm,
    borderTopWidth: 1,
    borderTopColor: colors.border,
  },
  clearBtn: {
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    borderRadius: radius.pill,
    borderWidth: 1,
    borderColor: colors.border,
    justifyContent: 'center',
  },
  clearText: { color: colors.textMuted, fontWeight: '700', fontSize: 15 },
  applyBtn: {
    flex: 1,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
    alignItems: 'center',
    justifyContent: 'center',
  },
  applyText: { color: colors.white, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
});
