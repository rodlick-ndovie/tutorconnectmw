import { useRef, useState } from 'react';
import {
  ScrollView,
  View,
  Text,
  TextInput,
  StyleSheet,
  Pressable,
  ActivityIndicator,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { useAuth } from '../src/store/auth';
import { metaApi } from '../src/api/endpoints';
import { useAvailability, type AvailabilityState } from '../src/hooks/useAvailability';
import { useKeyboardHeight } from '../src/hooks/useKeyboardHeight';
import { Select } from '../src/components/Select';
import { colors, radius, spacing } from '../src/theme/colors';

const GENDERS = ['Male', 'Female', 'Other', 'Prefer not to say'];
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const STEPS = [
  { n: 1, label: 'About you' },
  { n: 2, label: 'Location' },
  { n: 3, label: 'Account' },
];

export default function Register() {
  const router = useRouter();
  const register = useAuth((s) => s.register);
  const [step, setStep] = useState<1 | 2 | 3>(1);
  const [error, setError] = useState<string | null>(null);
  const [busy, setBusy] = useState(false);

  // Step 1 — about you
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [gender, setGender] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');

  // Step 2 — location & work
  const [district, setDistrict] = useState('');
  const [location, setLocation] = useState('');
  const [isEmployed, setIsEmployed] = useState(false);
  const [schoolName, setSchoolName] = useState('');

  // Step 3 — account
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');
  const [showPw, setShowPw] = useState(false);

  const districts = useQuery({ queryKey: ['meta', 'districts'], queryFn: metaApi.districts });

  // Keyboard handling: pad the scroll content by the keyboard height so the
  // form is always scrollable past it, and pull the focused field into view.
  const scrollRef = useRef<ScrollView>(null);
  const keyboardHeight = useKeyboardHeight();
  const scrollToField = (y: number) =>
    scrollRef.current?.scrollTo({ y: Math.max(y - 90, 0), animated: true });

  const emailOk = EMAIL_RE.test(email.trim());
  const emailAvail = useAvailability('email', email, emailOk);
  const phoneAvail = useAvailability('phone', phone, phone.trim().length >= 7);
  const userAvail = useAvailability('username', username, username.trim().length >= 3);

  const step1Valid =
    firstName.trim().length >= 2 &&
    lastName.trim().length >= 2 &&
    !!gender &&
    emailOk &&
    emailAvail.status === 'available' &&
    phone.trim().length >= 7 &&
    phoneAvail.status !== 'taken';

  const step2Valid = !!district && location.trim().length > 0;

  const step3Valid =
    username.trim().length >= 3 &&
    userAvail.status === 'available' &&
    password.length >= 8 &&
    password === confirm;

  const onSubmit = async () => {
    setError(null);
    setBusy(true);
    try {
      const res = await register({
        firstName: firstName.trim(),
        lastName: lastName.trim(),
        email: email.trim(),
        phone: phone.trim(),
        gender: gender || undefined,
        district: district || undefined,
        location: location.trim() || undefined,
        isEmployed,
        schoolName: isEmployed ? schoolName.trim() || undefined : undefined,
        username: username.trim(),
        password,
      });
      router.replace({ pathname: '/verify-otp', params: { email: res.email } });
    } catch (e) {
      setError((e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  const goBack = () => {
    if (step === 1) router.back();
    else setStep((s) => (s - 1) as 1 | 2);
  };

  return (
    <SafeAreaView style={styles.safe} edges={['top', 'bottom']}>
      <Stack.Screen options={{ headerShown: false }} />
        <ScrollView
          ref={scrollRef}
          contentContainerStyle={[styles.content, { paddingBottom: spacing.xxl + keyboardHeight }]}
          keyboardShouldPersistTaps="handled"
          keyboardDismissMode="on-drag"
          showsVerticalScrollIndicator={false}
        >
          <Pressable style={styles.back} onPress={goBack} hitSlop={10}>
            <Ionicons name="chevron-back" size={22} color={colors.textMuted} />
          </Pressable>

          <Image source={require('../assets/logo.png')} style={styles.logo} contentFit="contain" />

          {/* Step indicator */}
          <View style={styles.steps}>
            {STEPS.map((s, i) => (
              <View key={s.n} style={styles.stepSegment}>
                <StepDot n={s.n} label={s.label} active={step === s.n} done={step > s.n} />
                {i < STEPS.length - 1 && (
                  <View style={[styles.stepLine, step > s.n && styles.stepLineDone]} />
                )}
              </View>
            ))}
          </View>

          {step === 1 && (
            <>
              <Text style={styles.title}>About you</Text>
              <Text style={styles.intro}>Step 1 of 3 — your name and contact details.</Text>

              <View style={styles.row}>
                <View style={styles.half}>
                  <Field label="First name" value={firstName} onChangeText={setFirstName} />
                </View>
                <View style={styles.half}>
                  <Field label="Last name" value={lastName} onChangeText={setLastName} />
                </View>
              </View>

              <Text style={styles.label}>Gender</Text>
              <View style={styles.chips}>
                {GENDERS.map((g) => (
                  <Chip key={g} label={g} active={gender === g} onPress={() => setGender((c) => (c === g ? '' : g))} />
                ))}
              </View>

              <Field
                label="Email"
                value={email}
                onChangeText={setEmail}
                keyboardType="email-address"
                autoCapitalize="none"
                status={emailAvail}
                onFocusScroll={scrollToField}
              />
              <Field
                label="Phone"
                value={phone}
                onChangeText={setPhone}
                keyboardType="phone-pad"
                status={phoneAvail}
                onFocusScroll={scrollToField}
              />

              <PrimaryButton label="Continue" disabled={!step1Valid} onPress={() => setStep(2)} />
            </>
          )}

          {step === 2 && (
            <>
              <Text style={styles.title}>Where do you teach?</Text>
              <Text style={styles.intro}>Step 2 of 3 — your location and employment.</Text>

              {districts.isLoading ? (
                <ActivityIndicator color={colors.primary} style={{ marginVertical: spacing.md }} />
              ) : (
                <Select
                  label="District"
                  value={district || undefined}
                  options={districts.data ?? []}
                  placeholder="Select your district"
                  onChange={(v) => setDistrict(v ?? '')}
                />
              )}

              <Field
                label="Location / Area"
                value={location}
                onChangeText={setLocation}
                onFocusScroll={scrollToField}
              />

              <Pressable style={styles.toggleRow} onPress={() => setIsEmployed((v) => !v)}>
                <Text style={styles.toggleLabel}>I am currently employed</Text>
                <Ionicons
                  name={isEmployed ? 'toggle' : 'toggle-outline'}
                  size={34}
                  color={isEmployed ? colors.primary : colors.textLight}
                />
              </Pressable>
              {isEmployed && (
                <Field
                  label="School name"
                  value={schoolName}
                  onChangeText={setSchoolName}
                  onFocusScroll={scrollToField}
                />
              )}

              <PrimaryButton label="Continue" disabled={!step2Valid} onPress={() => setStep(3)} />
              <BackLink label="Back to your details" onPress={() => setStep(1)} />
            </>
          )}

          {step === 3 && (
            <>
              <Text style={styles.title}>Create your account</Text>
              <Text style={styles.intro}>Step 3 of 3 — choose a username and password.</Text>

              <Field
                label="Username"
                value={username}
                onChangeText={setUsername}
                autoCapitalize="none"
                status={userAvail}
                onFocusScroll={scrollToField}
              />

              <Field
                label="Password (min 8 characters)"
                value={password}
                onChangeText={setPassword}
                secureTextEntry={!showPw}
                placeholder="••••••••"
                onFocusScroll={scrollToField}
              />

              <Field
                label="Confirm password"
                value={confirm}
                onChangeText={setConfirm}
                secureTextEntry={!showPw}
                placeholder="••••••••"
                onFocusScroll={scrollToField}
              />

              <Pressable style={styles.showPwRow} onPress={() => setShowPw((s) => !s)}>
                <Ionicons
                  name={showPw ? 'eye-off-outline' : 'eye-outline'}
                  size={18}
                  color={colors.primary}
                />
                <Text style={styles.showPwText}>{showPw ? 'Hide' : 'Show'} passwords</Text>
              </Pressable>
              {confirm.length > 0 && confirm !== password && (
                <Text style={styles.errorInline}>Passwords do not match.</Text>
              )}

              {error && <Text style={styles.error}>{error}</Text>}

              <Pressable
                style={[styles.button, (!step3Valid || busy) && { opacity: 0.5 }]}
                onPress={onSubmit}
                disabled={!step3Valid || busy}
              >
                {busy ? (
                  <ActivityIndicator color={colors.white} />
                ) : (
                  <Text style={styles.buttonText}>Create Account</Text>
                )}
              </Pressable>
              <BackLink label="Back to location" onPress={() => setStep(2)} />
            </>
          )}

          <Pressable onPress={() => router.replace('/login')} style={styles.loginLink}>
            <Text style={styles.loginLinkText}>Already have an account? Log in</Text>
          </Pressable>
        </ScrollView>
    </SafeAreaView>
  );
}

function PrimaryButton({ label, disabled, onPress }: { label: string; disabled: boolean; onPress: () => void }) {
  return (
    <Pressable style={[styles.button, disabled && { opacity: 0.5 }]} onPress={onPress} disabled={disabled}>
      <Text style={styles.buttonText}>{label}</Text>
      <Ionicons name="arrow-forward" size={18} color={colors.white} />
    </Pressable>
  );
}

function BackLink({ label, onPress }: { label: string; onPress: () => void }) {
  return (
    <Pressable style={styles.backStep} onPress={onPress}>
      <Text style={styles.backStepText}>{label}</Text>
    </Pressable>
  );
}

function Chip({ label, active, onPress }: { label: string; active: boolean; onPress: () => void }) {
  return (
    <Pressable style={[styles.chip, active && styles.chipActive]} onPress={onPress}>
      <Text style={[styles.chipText, active && styles.chipTextActive]}>{label}</Text>
    </Pressable>
  );
}

/** Text field with optional live availability feedback. */
function Field({
  label,
  status,
  onFocusScroll,
  ...rest
}: {
  label: string;
  status?: AvailabilityState;
  /** Called with this field's y offset so the screen can scroll it above the keyboard. */
  onFocusScroll?: (y: number) => void;
} & React.ComponentProps<typeof TextInput>) {
  const y = useRef(0);
  const border =
    status?.status === 'taken'
      ? colors.danger
      : status?.status === 'available'
        ? colors.success
        : colors.border;

  return (
    <View
      style={{ marginBottom: spacing.md }}
      onLayout={(e) => {
        y.current = e.nativeEvent.layout.y;
      }}
    >
      <Text style={styles.label}>{label}</Text>
      <View style={[styles.inputWrap, { borderColor: border }]}>
        <TextInput
          {...rest}
          style={styles.input}
          placeholderTextColor={colors.textLight}
          onFocus={(e) => {
            onFocusScroll?.(y.current);
            rest.onFocus?.(e);
          }}
        />
        {status?.status === 'checking' && <ActivityIndicator size="small" color={colors.textLight} />}
        {status?.status === 'available' && <Ionicons name="checkmark-circle" size={18} color={colors.success} />}
        {status?.status === 'taken' && <Ionicons name="close-circle" size={18} color={colors.danger} />}
      </View>
      {status?.status === 'taken' && <Text style={styles.errorInline}>{status.message}</Text>}
    </View>
  );
}

function StepDot({ n, label, active, done }: { n: number; label: string; active: boolean; done: boolean }) {
  return (
    <View style={styles.stepItem}>
      <View style={[styles.stepDot, (active || done) && styles.stepDotActive]}>
        {done ? (
          <Ionicons name="checkmark" size={14} color={colors.white} />
        ) : (
          <Text style={[styles.stepNum, (active || done) && { color: colors.white }]}>{n}</Text>
        )}
      </View>
      <Text style={[styles.stepLabel, active && { color: colors.text, fontWeight: '700' }]}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.xl, paddingBottom: spacing.xxl * 2 },
  back: { alignSelf: 'flex-start', padding: spacing.xs },
  logo: { width: 190, height: 44, alignSelf: 'center', marginTop: spacing.sm },

  steps: { flexDirection: 'row', alignItems: 'flex-start', marginTop: spacing.xl },
  stepSegment: { flexDirection: 'row', alignItems: 'center', flex: 1 },
  stepItem: { alignItems: 'center', gap: 4, width: 66 },
  stepDot: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
    justifyContent: 'center',
  },
  stepDotActive: { backgroundColor: colors.primary, borderColor: colors.primary },
  stepNum: { fontSize: 12, fontWeight: '800', color: colors.textMuted },
  stepLabel: { fontSize: 10, color: colors.textLight, fontWeight: '600', textAlign: 'center' },
  stepLine: { flex: 1, height: 2, backgroundColor: colors.border, marginBottom: 18 },
  stepLineDone: { backgroundColor: colors.primary },

  title: { fontSize: 24, fontWeight: '800', color: colors.text, marginTop: spacing.xl },
  intro: { fontSize: 14, color: colors.textMuted, marginTop: 4, marginBottom: spacing.xl },

  row: { flexDirection: 'row', gap: spacing.md },
  half: { flex: 1 },

  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginBottom: 6 },
  inputWrap: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    backgroundColor: colors.surface,
    marginBottom: spacing.md,
  },
  input: { flex: 1, fontSize: 15, color: colors.text, paddingVertical: spacing.md },

  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md },
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

  toggleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: spacing.sm,
    marginBottom: spacing.sm,
  },
  toggleLabel: { fontSize: 15, color: colors.text },
  showPwRow: { flexDirection: 'row', alignItems: 'center', gap: 6, alignSelf: 'flex-start' },
  showPwText: { color: colors.primary, fontWeight: '600', fontSize: 13 },

  error: { color: colors.danger, fontSize: 13, marginTop: spacing.sm },
  errorInline: { color: colors.danger, fontSize: 12, marginTop: -spacing.sm, marginBottom: spacing.sm },

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
  backStep: { alignItems: 'center', marginTop: spacing.md },
  backStepText: { color: colors.textMuted, fontWeight: '600', fontSize: 14 , textAlign: 'center', paddingHorizontal: 2},
  loginLink: { alignItems: 'center', marginTop: spacing.xl },
  loginLinkText: { color: colors.primary, fontWeight: '600', fontSize: 14 , textAlign: 'center', paddingHorizontal: 2},
});
