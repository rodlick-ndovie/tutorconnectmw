import { useEffect, useState } from 'react';
import { ScrollView, View, Text, StyleSheet, Pressable, ActivityIndicator, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { meApi, metaApi } from '../../src/api/endpoints';
import { colors, radius, spacing } from '../../src/theme/colors';

type Structured = Record<string, { levels: Record<string, string[]> }>;

// Total subjects across every curriculum/level.
function countSubjects(s: Structured): number {
  let n = 0;
  for (const c of Object.values(s)) for (const list of Object.values(c.levels)) n += list.length;
  return n;
}

export default function Subjects() {
  const router = useRouter();
  const qc = useQueryClient();
  const profile = useQuery({ queryKey: ['me', 'profile'], queryFn: meApi.profile });
  // The plan's subject cap (0 = unlimited). Drives the counter and the guard.
  const sub = useQuery({ queryKey: ['me', 'subscription'], queryFn: meApi.subscription });
  const maxSubjects = sub.data?.maxSubjects ?? 0;
  const [data, setData] = useState<Structured>({});
  const total = countSubjects(data);
  const atLimit = maxSubjects > 0 && total >= maxSubjects;

  // Add-flow selection state.
  const [curriculum, setCurriculum] = useState<string | null>(null);
  const [level, setLevel] = useState<string | null>(null);

  useEffect(() => {
    if (profile.data?.structuredSubjects) setData(profile.data.structuredSubjects);
  }, [profile.data]);

  const curricula = useQuery({ queryKey: ['meta', 'curricula'], queryFn: metaApi.curricula });
  const levels = useQuery({
    queryKey: ['meta', 'levels', curriculum],
    queryFn: () => metaApi.levels(curriculum!),
    enabled: !!curriculum,
  });
  const subjects = useQuery({
    queryKey: ['meta', 'subjects', curriculum, level],
    queryFn: () => metaApi.subjects(curriculum!, level!),
    enabled: !!curriculum && !!level,
  });

  const save = useMutation({
    mutationFn: () => meApi.updateSubjects(data),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['me', 'profile'] });
      Alert.alert('Saved', 'Your subjects have been updated.');
      router.back();
    },
    onError: (e) => Alert.alert('Error', (e as Error).message),
  });

  const hasSubject = (subject: string) =>
    !!(curriculum && level && data[curriculum]?.levels?.[level]?.includes(subject));

  const toggleSubject = (subject: string) => {
    if (!curriculum || !level) return;
    const isAdding = !hasSubject(subject);
    // Block ADDING past the plan's cap (removing is always fine). The server
    // enforces this too, but stopping it here avoids a rejected save.
    if (isAdding && maxSubjects > 0 && total >= maxSubjects) {
      Alert.alert(
        'Subject limit reached',
        `Your ${sub.data?.planName ?? 'current'} plan allows up to ${maxSubjects} subject${maxSubjects === 1 ? '' : 's'}. ` +
          `Remove one, or upgrade your plan to add more.`
      );
      return;
    }
    setData((prev) => {
      const next: Structured = JSON.parse(JSON.stringify(prev));
      next[curriculum] ??= { levels: {} };
      next[curriculum].levels[level] ??= [];
      const arr = next[curriculum].levels[level];
      next[curriculum].levels[level] = arr.includes(subject)
        ? arr.filter((s) => s !== subject)
        : [...arr, subject];
      if (next[curriculum].levels[level].length === 0) delete next[curriculum].levels[level];
      if (Object.keys(next[curriculum].levels).length === 0) delete next[curriculum];
      return next;
    });
  };

  const removeSubject = (c: string, l: string, s: string) =>
    setData((prev) => {
      const next: Structured = JSON.parse(JSON.stringify(prev));
      next[c].levels[l] = next[c].levels[l].filter((x) => x !== s);
      if (next[c].levels[l].length === 0) delete next[c].levels[l];
      if (Object.keys(next[c].levels).length === 0) delete next[c];
      return next;
    });

  if (profile.isLoading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'My Subjects', headerShown: true }} />
      <ScrollView contentContainerStyle={styles.content}>
        <View style={styles.sectionRow}>
          <Text style={styles.section}>Your subjects</Text>
          <View style={[styles.counter, atLimit && styles.counterFull]}>
            <Text style={[styles.counterText, atLimit && styles.counterTextFull]}>
              {maxSubjects > 0 ? `${total} / ${maxSubjects}` : `${total} · Unlimited`}
            </Text>
          </View>
        </View>
        {atLimit && (
          <Text style={styles.limitHint}>
            You've reached your {sub.data?.planName ?? 'plan'} limit. Remove a subject or upgrade to add more.
          </Text>
        )}
        {Object.keys(data).length === 0 && <Text style={styles.muted}>None yet — add some below.</Text>}
        {Object.entries(data).map(([c, d]) => (
          <View key={c} style={styles.group}>
            <Text style={styles.curriculum}>{c}</Text>
            {Object.entries(d.levels).map(([l, subs]) => (
              <View key={l} style={{ marginTop: 4 }}>
                <Text style={styles.level}>{l}</Text>
                <View style={styles.chips}>
                  {subs.map((s) => (
                    <Pressable key={s} style={styles.removable} onPress={() => removeSubject(c, l, s)}>
                      <Text style={styles.removableText}>{s}</Text>
                      <Ionicons name="close" size={14} color={colors.danger} />
                    </Pressable>
                  ))}
                </View>
              </View>
            ))}
          </View>
        ))}

        <Text style={styles.section}>Add subjects</Text>
        <Text style={styles.pickLabel}>Curriculum</Text>
        <View style={styles.chips}>
          {curricula.data?.map((c) => (
            <Chip key={c} label={c} active={curriculum === c} onPress={() => { setCurriculum(c); setLevel(null); }} />
          ))}
        </View>

        {curriculum && (
          <>
            <Text style={styles.pickLabel}>Level</Text>
            {levels.isLoading ? (
              <ActivityIndicator color={colors.primary} />
            ) : (
              <View style={styles.chips}>
                {levels.data?.map((l) => (
                  <Chip key={l} label={l} active={level === l} onPress={() => setLevel(l)} />
                ))}
              </View>
            )}
          </>
        )}

        {curriculum && level && (
          <>
            <Text style={styles.pickLabel}>Subjects (tap to add/remove)</Text>
            {subjects.isLoading ? (
              <ActivityIndicator color={colors.primary} />
            ) : (
              <View style={styles.chips}>
                {subjects.data?.map((s) => (
                  <Chip key={s} label={s} active={hasSubject(s)} onPress={() => toggleSubject(s)} />
                ))}
              </View>
            )}
          </>
        )}

        <Pressable style={[styles.save, save.isPending && { opacity: 0.7 }]} onPress={() => save.mutate()} disabled={save.isPending}>
          {save.isPending ? <ActivityIndicator color={colors.white} /> : <Text style={styles.saveText}>Save Subjects</Text>}
        </Pressable>
      </ScrollView>
    </SafeAreaView>
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
  safe: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg },
  content: { padding: spacing.lg, paddingBottom: spacing.xxl },
  section: { fontSize: 17, fontWeight: '800', color: colors.text, marginTop: spacing.lg, marginBottom: spacing.sm },
  sectionRow: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' },
  counter: {
    paddingHorizontal: spacing.md,
    paddingVertical: 4,
    borderRadius: radius.pill,
    backgroundColor: colors.primaryBg,
    marginTop: spacing.lg,
  },
  counterFull: { backgroundColor: '#FDE8E8' },
  counterText: { fontSize: 13, fontWeight: '800', color: colors.primaryDark },
  counterTextFull: { color: colors.danger },
  limitHint: { fontSize: 13, color: colors.danger, marginBottom: spacing.sm },
  muted: { color: colors.textMuted },
  group: { marginBottom: spacing.md },
  curriculum: { fontSize: 15, fontWeight: '700', color: colors.primary },
  level: { fontSize: 13, fontWeight: '600', color: colors.text, marginTop: 4 },
  pickLabel: { fontSize: 13, fontWeight: '600', color: colors.textMuted, marginTop: spacing.md, marginBottom: 4 },
  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginTop: 4 },
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
  removable: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    paddingHorizontal: spacing.md,
    paddingVertical: 6,
    borderRadius: radius.pill,
    backgroundColor: colors.successBg,
  },
  removableText: { fontSize: 13, color: colors.success, fontWeight: '600' },
  save: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.xxl,
  },
  saveText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
