import { useEffect, useState } from 'react';
import { View, Text, TextInput, StyleSheet, Pressable, ActivityIndicator } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import LottieView from 'lottie-react-native';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useLocalSearchParams, useRouter } from 'expo-router';
import { useAuth } from '../src/store/auth';
import { authApi } from '../src/api/endpoints';
import { colors, radius, spacing } from '../src/theme/colors';

// The tutor dashboard lives on the Account tab.
const DASHBOARD = '/(tabs)/profile' as const;
const REDIRECT_DELAY_MS = 2600;

export default function VerifyOtp() {
  const router = useRouter();
  const { email } = useLocalSearchParams<{ email: string }>();
  const verifyOtp = useAuth((s) => s.verifyOtp);
  const [code, setCode] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [info, setInfo] = useState<string | null>(null);
  const [busy, setBusy] = useState(false);
  const [resending, setResending] = useState(false);
  const [verified, setVerified] = useState(false);

  const onVerify = async () => {
    setError(null);
    setInfo(null);
    setBusy(true);
    try {
      await verifyOtp(String(email), code.trim());
      setVerified(true); // show the success animation, then go to the dashboard
    } catch (e) {
      setError((e as Error).message);
      setBusy(false);
    }
  };

  // Let the Lottie play, then land the tutor on their dashboard.
  useEffect(() => {
    if (!verified) return;
    const t = setTimeout(() => router.replace(DASHBOARD), REDIRECT_DELAY_MS);
    return () => clearTimeout(t);
  }, [verified, router]);

  if (verified) {
    return (
      <SafeAreaView style={styles.safe} edges={['top', 'bottom']}>
        <Stack.Screen options={{ headerShown: false }} />
        <View style={styles.successWrap}>
          <LottieView
            source={require('../assets/success.json')}
            autoPlay
            loop={false}
            style={styles.lottie}
          />
          <Text style={styles.successTitle}>Account verified</Text>
          <Text style={styles.successSub}>
            Welcome to TutorConnect Malawi. Taking you to your dashboard…
          </Text>
          <Pressable style={styles.button} onPress={() => router.replace(DASHBOARD)}>
            <Text style={styles.buttonText}>Go to Dashboard</Text>
          </Pressable>
        </View>
      </SafeAreaView>
    );
  }

  const onResend = async () => {
    setError(null);
    setInfo(null);
    setResending(true);
    try {
      const res = await authApi.resendOtp(String(email));
      setInfo(res.message);
    } catch (e) {
      setError((e as Error).message);
    } finally {
      setResending(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['top', 'bottom']}>
      <Stack.Screen options={{ headerShown: false }} />
      <View style={styles.content}>
        <Pressable style={styles.back} onPress={() => router.back()} hitSlop={10}>
          <Ionicons name="chevron-back" size={22} color={colors.textMuted} />
        </Pressable>

        <Image source={require('../assets/logo.png')} style={styles.logo} contentFit="contain" />

        <Text style={styles.title}>Enter verification code</Text>
        <Text style={styles.sub}>
          We sent a 6-digit code to{'\n'}
          <Text style={styles.email}>{email}</Text>
        </Text>

        <TextInput
          style={styles.input}
          value={code}
          onChangeText={(v) => setCode(v.replace(/[^0-9]/g, '').slice(0, 6))}
          keyboardType="number-pad"
          placeholder="______"
          placeholderTextColor={colors.textLight}
          maxLength={6}
          textAlign="center"
        />

        {error && <Text style={styles.error}>{error}</Text>}
        {info && <Text style={styles.info}>{info}</Text>}

        <Pressable style={[styles.button, (code.length < 4 || busy) && { opacity: 0.6 }]} onPress={onVerify} disabled={code.length < 4 || busy}>
          {busy ? <ActivityIndicator color={colors.white} /> : <Text style={styles.buttonText}>Verify & Continue</Text>}
        </Pressable>

        <Pressable onPress={onResend} disabled={resending} style={styles.resend}>
          <Text style={styles.resendText}>{resending ? 'Sending…' : "Didn't get it? Resend code"}</Text>
        </Pressable>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.xl, alignItems: 'center' },
  successWrap: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: spacing.xl,
  },
  lottie: { width: 200, height: 200 },
  successTitle: {
    fontSize: 24,
    fontWeight: '800',
    color: colors.text,
    marginTop: spacing.md,
  },
  successSub: {
    fontSize: 15,
    color: colors.textMuted,
    textAlign: 'center',
    marginTop: spacing.sm,
    lineHeight: 21,
  },
  back: { alignSelf: 'flex-start', padding: spacing.xs },
  logo: { width: 200, height: 46, marginTop: spacing.sm },
  title: { fontSize: 22, fontWeight: '800', color: colors.text, marginTop: spacing.xl },
  sub: { fontSize: 14, color: colors.textMuted, textAlign: 'center', marginTop: spacing.sm, lineHeight: 20 },
  email: { color: colors.text, fontWeight: '700' },
  input: {
    width: '70%',
    borderWidth: 1.5,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingVertical: spacing.md,
    fontSize: 28,
    letterSpacing: 8,
    color: colors.text,
    backgroundColor: colors.surface,
    marginTop: spacing.xl,
  },
  error: { color: colors.danger, fontSize: 13, marginTop: spacing.md, textAlign: 'center' },
  info: { color: colors.success, fontSize: 13, marginTop: spacing.md, textAlign: 'center' },
  button: {
    width: '100%',
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.xl,
  },
  buttonText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
  resend: { marginTop: spacing.lg },
  resendText: { color: colors.primary, fontWeight: '600', fontSize: 14 },
});
