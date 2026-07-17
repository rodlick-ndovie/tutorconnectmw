import { useMemo, useState } from 'react';
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
import { useQuery } from '@tanstack/react-query';
import { universityApi } from '../../src/api/endpoints';
import { Select } from '../../src/components/Select';
import { useKeyboardHeight } from '../../src/hooks/useKeyboardHeight';
import { colors, radius, spacing } from '../../src/theme/colors';

export default function RequestLecture() {
  const router = useRouter();
  const meta = useQuery({ queryKey: ['university', 'meta'], queryFn: universityApi.meta });
  const keyboardHeight = useKeyboardHeight();

  const [fullName, setFullName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [institution, setInstitution] = useState('');
  const [category, setCategory] = useState<string>();
  const [topic, setTopic] = useState<string>();
  const [deliveryMode, setDeliveryMode] = useState<string>();
  const [cityLocation, setCityLocation] = useState('');
  const [preferredTime, setPreferredTime] = useState<string>();
  const [budgetRange, setBudgetRange] = useState('');
  const [notes, setNotes] = useState('');
  const [busy, setBusy] = useState(false);

  const categories = useMemo(
    () => Object.keys(meta.data?.serviceCategories ?? {}),
    [meta.data]
  );
  const topics = useMemo(
    () => (category ? (meta.data?.serviceCategories?.[category] ?? []) : []),
    [meta.data, category]
  );

  const valid =
    fullName.trim().length >= 5 &&
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim()) &&
    phone.trim().length >= 8 &&
    institution.trim() &&
    category &&
    topic &&
    deliveryMode &&
    cityLocation.trim();

  const submit = async () => {
    setBusy(true);
    try {
      const res = await universityApi.requestLecture({
        fullName: fullName.trim(),
        email: email.trim(),
        phone: phone.trim(),
        institution: institution.trim(),
        serviceCategory: category!,
        topic: topic!,
        deliveryMode: deliveryMode!,
        cityLocation: cityLocation.trim(),
        preferredTime,
        budgetRange: budgetRange.trim() || undefined,
        notes: notes.trim() || undefined,
      });
      Alert.alert(
        'Request submitted',
        `${res.message}\n\nYour reference: ${res.referenceCode}`,
        [{ text: 'Done', onPress: () => router.back() }]
      );
    } catch (e) {
      Alert.alert('Could not submit', (e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Request a Lecture', headerShown: true }} />
      <ScrollView
        contentContainerStyle={[styles.content, { paddingBottom: spacing.xxl + keyboardHeight }]}
        keyboardShouldPersistTaps="handled"
        keyboardDismissMode="on-drag"
        showsVerticalScrollIndicator={false}
      >
        <Text style={styles.intro}>
          Tell us what you need help with. Matching university tutors and companies will be notified
          and will contact you directly.
        </Text>

        <Field label="Your full name" value={fullName} onChangeText={setFullName} />
        <Field label="Email" value={email} onChangeText={setEmail} keyboardType="email-address" autoCapitalize="none" />
        <Field label="Phone" value={phone} onChangeText={setPhone} keyboardType="phone-pad" />
        <Field label="Institution" value={institution} onChangeText={setInstitution} placeholder="e.g. University of Malawi" />

        {meta.isLoading ? (
          <ActivityIndicator color={colors.primary} style={{ marginVertical: spacing.lg }} />
        ) : (
          <>
            <Select
              label="Service category"
              value={category}
              options={categories}
              placeholder="Select a category"
              onChange={(v) => {
                setCategory(v);
                setTopic(undefined); // topics depend on the category
              }}
            />
            <Select
              label="Topic"
              value={topic}
              options={topics}
              placeholder={category ? 'Select a topic' : 'Pick a category first'}
              onChange={setTopic}
            />
            <Select
              label="Delivery mode"
              value={deliveryMode}
              options={meta.data?.teachingModes ?? []}
              placeholder="Online, Physical or Both"
              searchable={false}
              onChange={setDeliveryMode}
            />
            <Select
              label="Preferred time (optional)"
              value={preferredTime}
              options={meta.data?.preferredTimes ?? []}
              placeholder="Any time"
              searchable={false}
              onChange={setPreferredTime}
            />
          </>
        )}

        <Field label="City / location" value={cityLocation} onChangeText={setCityLocation} />
        <Field label="Budget range (optional)" value={budgetRange} onChangeText={setBudgetRange} placeholder="e.g. MWK 20,000 - 40,000" />
        <Field label="Notes (optional)" value={notes} onChangeText={setNotes} multiline />

        <Pressable style={[styles.button, (!valid || busy) && { opacity: 0.5 }]} onPress={submit} disabled={!valid || busy}>
          {busy ? (
            <ActivityIndicator color={colors.white} />
          ) : (
            <>
              <Ionicons name="send" size={18} color={colors.white} />
              <Text style={styles.buttonText}>Submit Request</Text>
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
        style={[styles.input, rest.multiline && { height: 100, textAlignVertical: 'top' }]}
        placeholderTextColor={colors.textLight}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.lg },
  intro: { fontSize: 14, color: colors.textMuted, lineHeight: 20, marginBottom: spacing.lg },
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
