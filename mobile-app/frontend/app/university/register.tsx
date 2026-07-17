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
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { universityApi, metaApi, type UploadFile } from '../../src/api/endpoints';
import { pickImage, pickDocument } from '../../src/lib/filePicker';
import { Select } from '../../src/components/Select';
import { useAvailability, type AvailabilityState } from '../../src/hooks/useAvailability';
import { useKeyboardHeight } from '../../src/hooks/useKeyboardHeight';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { UniAccountType } from '../../src/types';

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const STEPS = ['Account', 'Expertise', 'Documents'];

export default function UniversityRegister() {
  const router = useRouter();
  const meta = useQuery({ queryKey: ['university', 'meta'], queryFn: universityApi.meta });
  const districts = useQuery({ queryKey: ['meta', 'districts'], queryFn: metaApi.districts });
  const keyboardHeight = useKeyboardHeight();

  const [step, setStep] = useState(0);
  const [busy, setBusy] = useState(false);

  // Step 1 — account
  const [accountType, setAccountType] = useState<UniAccountType>('individual');
  const [fullName, setFullName] = useState('');
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  // Step 2 — expertise
  const [institutions, setInstitutions] = useState('');
  const [category, setCategory] = useState<string>();
  const [specializations, setSpecializations] = useState<string[]>([]);
  const [year, setYear] = useState('');
  const [bio, setBio] = useState('');
  const [teachingMode, setTeachingMode] = useState<string>();
  const [district, setDistrict] = useState<string>();
  const [cityLocation, setCityLocation] = useState('');

  // Step 3 — documents + rates
  const [profilePhoto, setProfilePhoto] = useState<UploadFile | null>(null);
  const [nationalId, setNationalId] = useState<UploadFile | null>(null);
  const [certificates, setCertificates] = useState<UploadFile[]>([]);
  const [hourlyRate, setHourlyRate] = useState('');
  const [plan, setPlan] = useState<string>('Basic');

  const isFirm = accountType === 'firm';
  const emailOk = EMAIL_RE.test(email.trim());
  const emailAvail = useAvailability('email', email, emailOk);
  const userAvail = useAvailability('username', username, username.trim().length >= 3);

  const categories = useMemo(() => Object.keys(meta.data?.serviceCategories ?? {}), [meta.data]);
  const topics = useMemo(
    () => (category ? (meta.data?.serviceCategories?.[category] ?? []) : []),
    [meta.data, category]
  );

  const step1Valid =
    fullName.trim().length >= 5 &&
    firstName.trim() &&
    lastName.trim() &&
    emailOk &&
    emailAvail.status === 'available' &&
    phone.trim().length >= 8 &&
    username.trim().length >= 3 &&
    userAvail.status === 'available' &&
    password.length >= 8;

  const step2Valid =
    !!category && specializations.length > 0 && year.trim() && bio.trim().length >= 40 && !!teachingMode && cityLocation.trim();

  const step3Valid = !!profilePhoto && !!nationalId;

  async function pick(kind: 'photo' | 'id' | 'cert') {
    // Photo = image only. ID / certificates may be a PDF from Files.
    const file = kind === 'photo' ? await pickImage() : await pickDocument();
    if (!file) return;
    if (kind === 'photo') setProfilePhoto(file);
    else if (kind === 'id') setNationalId(file);
    else setCertificates((c) => [...c, file].slice(0, 5));
  }

  const submit = async () => {
    setBusy(true);
    try {
      const res = await universityApi.register(
        {
          accountType,
          fullName: fullName.trim(),
          firstName: firstName.trim(),
          lastName: lastName.trim(),
          email: email.trim(),
          phone: phone.trim(),
          username: username.trim(),
          password,
          district,
          cityLocation: cityLocation.trim(),
          yearOfStudyOrGraduation: year.trim(),
          bio: bio.trim(),
          teachingMode: teachingMode!,
          subscriptionPlan: plan,
          hourlyRate: hourlyRate.trim() || undefined,
          institutions: institutions
            .split(',')
            .map((s) => s.trim())
            .filter(Boolean),
          specializations,
          serviceAreas: category ? [category] : [],
        },
        { profilePhoto: profilePhoto!, nationalId: nationalId!, certificates }
      );
      Alert.alert(
        'Registration received',
        `${res.message}\n\nReference: ${res.referenceCode}`,
        [{ text: 'Verify email', onPress: () => router.replace({ pathname: '/verify-otp', params: { email: res.email } }) }]
      );
    } catch (e) {
      Alert.alert('Registration failed', (e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Join University Support', headerShown: true }} />
      <ScrollView
        contentContainerStyle={[styles.content, { paddingBottom: spacing.xxl + keyboardHeight }]}
        keyboardShouldPersistTaps="handled"
        keyboardDismissMode="on-drag"
        showsVerticalScrollIndicator={false}
      >
        {/* Steps */}
        <View style={styles.steps}>
          {STEPS.map((s, i) => (
            <View key={s} style={styles.stepItem}>
              <View style={[styles.stepDot, i <= step && styles.stepDotActive]}>
                {i < step ? (
                  <Ionicons name="checkmark" size={13} color={colors.white} />
                ) : (
                  <Text style={[styles.stepNum, i <= step && { color: colors.white }]}>{i + 1}</Text>
                )}
              </View>
              <Text style={[styles.stepLabel, i === step && { color: colors.text, fontWeight: '700' }]}>{s}</Text>
            </View>
          ))}
        </View>

        {step === 0 && (
          <>
            <Text style={styles.title}>Who is joining?</Text>
            <View style={styles.typeRow}>
              <TypeCard
                icon="person"
                title="Individual"
                sub="A university/college tutor"
                active={!isFirm}
                onPress={() => setAccountType('individual')}
              />
              <TypeCard
                icon="business"
                title="Company"
                sub="A firm offering services"
                active={isFirm}
                onPress={() => setAccountType('firm')}
              />
            </View>

            <Field
              label={isFirm ? 'Company name' : 'Full name'}
              value={fullName}
              onChangeText={setFullName}
            />
            <View style={styles.row}>
              <View style={styles.half}>
                <Field label={isFirm ? 'Contact first name' : 'First name'} value={firstName} onChangeText={setFirstName} />
              </View>
              <View style={styles.half}>
                <Field label={isFirm ? 'Contact last name' : 'Last name'} value={lastName} onChangeText={setLastName} />
              </View>
            </View>
            <Field label="Email" value={email} onChangeText={setEmail} keyboardType="email-address" autoCapitalize="none" status={emailAvail} />
            <Field label="Phone" value={phone} onChangeText={setPhone} keyboardType="phone-pad" />
            <Field label="Username" value={username} onChangeText={setUsername} autoCapitalize="none" status={userAvail} />
            <Field label="Password (min 8 characters)" value={password} onChangeText={setPassword} secureTextEntry />

            <Primary label="Continue" disabled={!step1Valid} onPress={() => setStep(1)} />
          </>
        )}

        {step === 1 && (
          <>
            <Text style={styles.title}>Your expertise</Text>

            <Select
              label="Service area"
              value={category}
              options={categories}
              placeholder="Select a service area"
              onChange={(v) => {
                setCategory(v);
                setSpecializations([]);
              }}
            />

            {category && (
              <>
                <Text style={styles.label}>Specializations</Text>
                <View style={styles.chips}>
                  {topics.map((t) => {
                    const on = specializations.includes(t);
                    return (
                      <Pressable
                        key={t}
                        style={[styles.chip, on && styles.chipActive]}
                        onPress={() =>
                          setSpecializations((s) => (on ? s.filter((x) => x !== t) : [...s, t]))
                        }
                      >
                        <Text style={[styles.chipText, on && styles.chipTextActive]}>{t}</Text>
                      </Pressable>
                    );
                  })}
                </View>
              </>
            )}

            <Field
              label={isFirm ? 'Institutions served (comma separated)' : 'Institutions (comma separated)'}
              value={institutions}
              onChangeText={setInstitutions}
              placeholder="University of Malawi, MUBAS"
            />
            <Field
              label={isFirm ? 'Year established' : 'Year of study / graduation'}
              value={year}
              onChangeText={setYear}
              placeholder="e.g. 2019"
            />
            <Select
              label="Teaching / delivery mode"
              value={teachingMode}
              options={meta.data?.teachingModes ?? []}
              placeholder="Online, Physical or Both"
              searchable={false}
              onChange={setTeachingMode}
            />
            <Select
              label="District"
              value={district}
              options={districts.data ?? []}
              placeholder="Select district"
              onChange={setDistrict}
            />
            <Field label="City / location" value={cityLocation} onChangeText={setCityLocation} />
            <Field
              label={isFirm ? 'Company profile (min 40 characters)' : 'Bio (min 40 characters)'}
              value={bio}
              onChangeText={setBio}
              multiline
            />

            <Primary label="Continue" disabled={!step2Valid} onPress={() => setStep(2)} />
            <Back onPress={() => setStep(0)} />
          </>
        )}

        {step === 2 && (
          <>
            <Text style={styles.title}>Documents & rates</Text>
            <Text style={styles.hint}>
              A profile photo and a national ID are required. Certificates are optional but speed up
              approval.
            </Text>

            <Upload
              label={isFirm ? 'Company logo / photo' : 'Profile photo'}
              file={profilePhoto}
              required
              onPick={() => pick('photo')}
              onClear={() => setProfilePhoto(null)}
            />
            <Upload
              label={isFirm ? 'Company registration / ID' : 'National ID'}
              file={nationalId}
              required
              onPick={() => pick('id')}
              onClear={() => setNationalId(null)}
            />

            <Text style={styles.label}>Certificates (up to 5)</Text>
            {certificates.map((c, i) => (
              <View key={`${c.uri}-${i}`} style={styles.fileRow}>
                <Ionicons name="document-attach" size={18} color={colors.primary} />
                <Text style={styles.fileName} numberOfLines={1}>
                  {c.name}
                </Text>
                <Pressable onPress={() => setCertificates((x) => x.filter((_, j) => j !== i))} hitSlop={8}>
                  <Ionicons name="close-circle" size={18} color={colors.danger} />
                </Pressable>
              </View>
            ))}
            {certificates.length < 5 && (
              <Pressable style={styles.addBtn} onPress={() => pick('cert')}>
                <Ionicons name="add" size={18} color={colors.primary} />
                <Text style={styles.addText}>Add certificate</Text>
              </Pressable>
            )}

            <View style={{ height: spacing.md }} />
            <Field label="Hourly rate (MWK, optional)" value={hourlyRate} onChangeText={setHourlyRate} keyboardType="number-pad" />
            <Select
              label="Subscription plan"
              value={plan}
              options={meta.data?.plans ?? ['Basic', 'Standard', 'Premium']}
              searchable={false}
              allowClear={false}
              onChange={(v) => setPlan(v ?? 'Basic')}
            />

            <Pressable
              style={[styles.primary, (!step3Valid || busy) && { opacity: 0.5 }]}
              onPress={submit}
              disabled={!step3Valid || busy}
            >
              {busy ? (
                <ActivityIndicator color={colors.white} />
              ) : (
                <Text style={styles.primaryText}>Submit Registration</Text>
              )}
            </Pressable>
            <Back onPress={() => setStep(1)} />
          </>
        )}
      </ScrollView>
    </SafeAreaView>
  );
}

function TypeCard({
  icon,
  title,
  sub,
  active,
  onPress,
}: {
  icon: keyof typeof Ionicons.glyphMap;
  title: string;
  sub: string;
  active: boolean;
  onPress: () => void;
}) {
  return (
    <Pressable style={[styles.typeCard, active && styles.typeCardActive]} onPress={onPress}>
      <Ionicons name={icon} size={24} color={active ? colors.primary : colors.textMuted} />
      <Text style={[styles.typeTitle, active && { color: colors.primary }]}>{title}</Text>
      <Text style={styles.typeSub}>{sub}</Text>
    </Pressable>
  );
}

function Upload({
  label,
  file,
  required,
  onPick,
  onClear,
}: {
  label: string;
  file: UploadFile | null;
  required?: boolean;
  onPick: () => void;
  onClear: () => void;
}) {
  return (
    <View style={{ marginBottom: spacing.md }}>
      <Text style={styles.label}>
        {label} {required && <Text style={{ color: colors.danger }}>*</Text>}
      </Text>
      {file ? (
        <View style={styles.uploaded}>
          <Image source={{ uri: file.uri }} style={styles.thumb} contentFit="cover" />
          <Text style={styles.fileName} numberOfLines={1}>
            {file.name}
          </Text>
          <Pressable onPress={onClear} hitSlop={8}>
            <Ionicons name="close-circle" size={20} color={colors.danger} />
          </Pressable>
        </View>
      ) : (
        <Pressable style={styles.addBtn} onPress={onPick}>
          <Ionicons name="cloud-upload-outline" size={18} color={colors.primary} />
          <Text style={styles.addText}>Upload</Text>
        </Pressable>
      )}
    </View>
  );
}

function Field({
  label,
  status,
  ...rest
}: { label: string; status?: AvailabilityState } & React.ComponentProps<typeof TextInput>) {
  const border =
    status?.status === 'taken' ? colors.danger : status?.status === 'available' ? colors.success : colors.border;
  return (
    <View style={{ marginBottom: spacing.md }}>
      <Text style={styles.label}>{label}</Text>
      <View style={[styles.inputWrap, { borderColor: border }]}>
        <TextInput
          {...rest}
          style={[styles.input, rest.multiline && { height: 100, textAlignVertical: 'top' }]}
          placeholderTextColor={colors.textLight}
        />
        {status?.status === 'checking' && <ActivityIndicator size="small" color={colors.textLight} />}
        {status?.status === 'available' && <Ionicons name="checkmark-circle" size={18} color={colors.success} />}
        {status?.status === 'taken' && <Ionicons name="close-circle" size={18} color={colors.danger} />}
      </View>
      {status?.status === 'taken' && <Text style={styles.err}>{status.message}</Text>}
    </View>
  );
}

const Primary = ({ label, disabled, onPress }: { label: string; disabled: boolean; onPress: () => void }) => (
  <Pressable style={[styles.primary, disabled && { opacity: 0.5 }]} onPress={onPress} disabled={disabled}>
    <Text style={styles.primaryText}>{label}</Text>
    <Ionicons name="arrow-forward" size={18} color={colors.white} />
  </Pressable>
);

const Back = ({ onPress }: { onPress: () => void }) => (
  <Pressable style={styles.back} onPress={onPress}>
    <Text style={styles.backText}>Back</Text>
  </Pressable>
);

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.lg },

  steps: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: spacing.lg },
  stepItem: { alignItems: 'center', gap: 4, flex: 1 },
  stepDot: {
    width: 26,
    height: 26,
    borderRadius: 13,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
    justifyContent: 'center',
  },
  stepDotActive: { backgroundColor: colors.primary, borderColor: colors.primary },
  stepNum: { fontSize: 11, fontWeight: '800', color: colors.textMuted },
  stepLabel: { fontSize: 11, color: colors.textLight, fontWeight: '600' },

  title: { fontSize: 22, fontWeight: '800', color: colors.text, marginBottom: spacing.md },
  hint: { fontSize: 13, color: colors.textMuted, marginBottom: spacing.lg, lineHeight: 18 },

  typeRow: { flexDirection: 'row', gap: spacing.md, marginBottom: spacing.lg },
  typeCard: {
    flex: 1,
    alignItems: 'center',
    gap: 4,
    padding: spacing.md,
    borderRadius: radius.lg,
    borderWidth: 1.5,
    borderColor: colors.border,
    backgroundColor: colors.surface,
  },
  typeCardActive: { borderColor: colors.primary, backgroundColor: colors.primaryBg },
  typeTitle: { fontSize: 14, fontWeight: '800', color: colors.text },
  typeSub: { fontSize: 11, color: colors.textMuted, textAlign: 'center' },

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
  },
  input: { flex: 1, fontSize: 15, color: colors.text, paddingVertical: spacing.md },
  err: { color: colors.danger, fontSize: 12, marginTop: 4 },

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

  addBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 6,
    borderWidth: 1.5,
    borderStyle: 'dashed',
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingVertical: spacing.md,
  },
  addText: { color: colors.primary, fontWeight: '700', fontSize: 14 },
  uploaded: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    padding: spacing.sm,
    backgroundColor: colors.surface,
  },
  thumb: { width: 36, height: 36, borderRadius: radius.sm, backgroundColor: colors.border },
  fileRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    padding: spacing.sm,
    marginBottom: spacing.sm,
    backgroundColor: colors.surface,
  },
  fileName: { flex: 1, fontSize: 13, color: colors.text },

  primary: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    marginTop: spacing.lg,
  },
  primaryText: { color: colors.white, fontWeight: '800', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
  back: { alignItems: 'center', marginTop: spacing.md },
  backText: { color: colors.textMuted, fontWeight: '600', fontSize: 14 },
});
