import { useEffect, useState } from 'react';
import { ScrollView, View, Text, StyleSheet, Pressable, ActivityIndicator, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { meApi } from '../../src/api/endpoints';
import { colors, radius, spacing } from '../../src/theme/colors';

const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
const TIMES = ['Morning (8AM-12PM)', 'Afternoon (12PM-5PM)', 'Evening (5PM-9PM)'];

export default function Availability() {
  const router = useRouter();
  const qc = useQueryClient();
  const profile = useQuery({ queryKey: ['me', 'profile'], queryFn: meApi.profile });
  const [days, setDays] = useState<string[]>([]);
  const [times, setTimes] = useState<string[]>([]);

  useEffect(() => {
    if (profile.data?.availability) {
      setDays(profile.data.availability.days ?? []);
      setTimes(profile.data.availability.times ?? []);
    }
  }, [profile.data]);

  const toggle = (list: string[], set: (v: string[]) => void, item: string) =>
    set(list.includes(item) ? list.filter((i) => i !== item) : [...list, item]);

  const save = useMutation({
    mutationFn: () => meApi.updateAvailability({ days, times }),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['me', 'profile'] });
      Alert.alert('Saved', 'Availability updated.');
      router.back();
    },
    onError: (e) => Alert.alert('Error', (e as Error).message),
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
      <Stack.Screen options={{ title: 'Availability', headerShown: true }} />
      <ScrollView contentContainerStyle={styles.content}>
        <Text style={styles.section}>Available days</Text>
        <View style={styles.chips}>
          {DAYS.map((d) => (
            <Chip key={d} label={d} active={days.includes(d)} onPress={() => toggle(days, setDays, d)} />
          ))}
        </View>

        <Text style={styles.section}>Available times</Text>
        <View style={styles.chips}>
          {TIMES.map((t) => (
            <Chip key={t} label={t} active={times.includes(t)} onPress={() => toggle(times, setTimes, t)} />
          ))}
        </View>

        <Pressable style={[styles.save, save.isPending && { opacity: 0.7 }]} onPress={() => save.mutate()} disabled={save.isPending}>
          {save.isPending ? <ActivityIndicator color={colors.white} /> : <Text style={styles.saveText}>Save Availability</Text>}
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
  content: { padding: spacing.lg },
  section: { fontSize: 16, fontWeight: '700', color: colors.text, marginTop: spacing.lg, marginBottom: spacing.sm },
  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm },
  chip: {
    paddingHorizontal: spacing.md,
    paddingVertical: 8,
    borderRadius: radius.pill,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
  },
  chipActive: { backgroundColor: colors.primary, borderColor: colors.primary },
  chipText: { fontSize: 14, color: colors.textMuted, fontWeight: '600' },
  chipTextActive: { color: colors.white },
  save: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.xxl,
  },
  saveText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
