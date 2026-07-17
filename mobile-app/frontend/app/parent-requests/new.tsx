import { useState } from 'react';
import {
  ScrollView,
  View,
  Text,
  TextInput,
  StyleSheet,
  Pressable,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import { parentRequestsApi, metaApi } from '../../src/api/endpoints';
import { Select } from '../../src/components/Select';
import { useKeyboardHeight } from '../../src/hooks/useKeyboardHeight';
import { colors, radius, spacing } from '../../src/theme/colors';

const MODES: { key: 'online' | 'in-person' | 'both'; label: string }[] = [
  { key: 'online', label: 'Online' },
  { key: 'in-person', label: 'In person' },
  { key: 'both', label: 'Both' },
];

export default function NewParentRequest() {
  const router = useRouter();
  const qc = useQueryClient();
  const keyboardHeight = useKeyboardHeight();

  const districts = useQuery({ queryKey: ['meta', 'districts'], queryFn: metaApi.districts });
  const curricula = useQuery({ queryKey: ['meta', 'curricula'], queryFn: metaApi.curricula });

  const [curriculum, setCurriculum] = useState<string>();
  const [district, setDistrict] = useState<string>();
  const [gradeClass, setGradeClass] = useState('');
  const [subjects, setSubjects] = useState('');
  const [specificLocation, setSpecificLocation] = useState('');
  const [mode, setMode] = useState<'online' | 'in-person' | 'both'>('both');
  const [budgetMin, setBudgetMin] = useState('');
  const [budgetMax, setBudgetMax] = useState('');
  const [notes, setNotes] = useState('');
  const [parentPhone, setParentPhone] = useState('');
  const [parentEmail, setParentEmail] = useState('');
  const [busy, setBusy] = useState(false);

  const valid =
    !!curriculum &&
    !!district &&
    gradeClass.trim() &&
    subjects.trim() &&
    parentPhone.trim().length >= 8 &&
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(parentEmail.trim());

  const submit = async () => {
    setBusy(true);
    try {
      const res = await parentRequestsApi.create({
        curriculum: curriculum!,
        gradeClass: gradeClass.trim(),
        subjects: subjects
          .split(',')
          .map((s) => s.trim())
          .filter(Boolean),
        district: district!,
        specificLocation: specificLocation.trim() || undefined,
        mode,
        budgetMin: budgetMin ? Number(budgetMin.replace(/[^\d]/g, '')) : undefined,
        budgetMax: budgetMax ? Number(budgetMax.replace(/[^\d]/g, '')) : undefined,
        budgetPeriod: budgetMin || budgetMax ? 'month' : undefined,
        notes: notes.trim() || undefined,
        parentPhone: parentPhone.trim(),
        parentEmail: parentEmail.trim(),
      });
      qc.invalidateQueries({ queryKey: ['parent-requests'] });
      Alert.alert(
        'Request posted',
        `Tutors will be notified and will contact you.\n\nReference: ${res.referenceCode}`,
        [{ text: 'Done', onPress: () => router.back() }]
      );
    } catch (e) {
      Alert.alert('Could not post', (e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Post a Request', headerShown: true }} />
      <ScrollView
        contentContainerStyle={[styles.content, { paddingBottom: spacing.xxl + keyboardHeight }]}
        keyboardShouldPersistTaps="handled"
        keyboardDismissMode="on-drag"
        showsVerticalScrollIndicator={false}
      >
        <Text style={styles.intro}>
          Describe what you need. Matching tutors will be notified and can reach out to you.
        </Text>

        <Select
          label="Curriculum"
          value={curriculum}
          options={curricula.data ?? []}
          placeholder="Select curriculum"
          onChange={setCurriculum}
        />
        <Field label="Grade / class" value={gradeClass} onChangeText={setGradeClass} placeholder="e.g. Form 3" />
        <Field
          label="Subjects (comma separated)"
          value={subjects}
          onChangeText={setSubjects}
          placeholder="Mathematics, Physics"
        />
        <Select
          label="District"
          value={district}
          options={districts.data ?? []}
          placeholder="Select district"
          onChange={setDistrict}
        />
        <Field label="Specific location (optional)" value={specificLocation} onChangeText={setSpecificLocation} />

        <Text style={styles.label}>Mode</Text>
        <View style={styles.chips}>
          {MODES.map((m) => (
            <Pressable
              key={m.key}
              style={[styles.chip, mode === m.key && styles.chipActive]}
              onPress={() => setMode(m.key)}
            >
              <Text style={[styles.chipText, mode === m.key && styles.chipTextActive]}>{m.label}</Text>
            </Pressable>
          ))}
        </View>

        <View style={styles.row}>
          <View style={styles.half}>
            <Field label="Budget min (MWK)" value={budgetMin} onChangeText={setBudgetMin} keyboardType="number-pad" />
          </View>
          <View style={styles.half}>
            <Field label="Budget max (MWK)" value={budgetMax} onChangeText={setBudgetMax} keyboardType="number-pad" />
          </View>
        </View>

        <Field label="Your phone" value={parentPhone} onChangeText={setParentPhone} keyboardType="phone-pad" />
        <Field label="Your email" value={parentEmail} onChangeText={setParentEmail} keyboardType="email-address" autoCapitalize="none" />
        <Field label="Notes (optional)" value={notes} onChangeText={setNotes} multiline />

        <Pressable style={[styles.button, (!valid || busy) && { opacity: 0.5 }]} onPress={submit} disabled={!valid || busy}>
          {busy ? (
            <ActivityIndicator color={colors.white} />
          ) : (
            <>
              <Ionicons name="send" size={18} color={colors.white} />
              <Text style={styles.buttonText}>Post Request</Text>
            </>
          )}
        </Pressable>
      </ScrollView>
    </SafeAreaView>
  );
}

function Field({ label, ...rest }: { label: string } & React.ComponentProps<typeof TextInput>) {
  return (
    <View style={{ marginBottom: spacing.md }}>
      <Text style={styles.label}>{label}</Text>
      <TextInput
        {...rest}
        style={[styles.input, rest.multiline && { height: 90, textAlignVertical: 'top' }]}
        placeholderTextColor={colors.textLight}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.lg },
  intro: { fontSize: 13, color: colors.textMuted, lineHeight: 19, marginBottom: spacing.lg },
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginBottom: 6 },
  input: {
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    fontSize: 15,
    color: colors.text,
    backgroundColor: colors.surface,
  },
  row: { flexDirection: 'row', gap: spacing.md },
  half: { flex: 1 },
  chips: { flexDirection: 'row', gap: spacing.sm, marginBottom: spacing.md },
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
  button: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    marginTop: spacing.lg,
  },
  buttonText: { color: colors.white, fontWeight: '800', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
