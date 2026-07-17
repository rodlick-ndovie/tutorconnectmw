import { useState } from 'react';
import { View, Text, TextInput, StyleSheet, Pressable, ActivityIndicator, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useLocalSearchParams, useRouter } from 'expo-router';
import { authApi } from '../src/api/endpoints';
import { colors, radius, spacing } from '../src/theme/colors';

export default function ResetPassword() {
  const router = useRouter();
  const { email } = useLocalSearchParams<{ email: string }>();
  const [code, setCode] = useState('');
  const [password, setPassword] = useState('');
  const [showPw, setShowPw] = useState(false);
  const [busy, setBusy] = useState(false);
  const [resending, setResending] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [info, setInfo] = useState<string | null>(null);

  const valid = code.trim().length >= 4 && password.length >= 8;

  const onSubmit = async () => {
    setError(null);
    setInfo(null);
    setBusy(true);
    try {
      await authApi.resetPassword(String(email), code.trim(), password);
      router.replace({ pathname: '/login', params: { reset: '1' } });
    } catch (e) {
      setError((e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  const onResend = async () => {
    setError(null);
    setInfo(null);
    setResending(true);
    try {
      const res = await authApi.forgotPassword(String(email));
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
      <ScrollView contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <Pressable style={styles.back} onPress={() => router.back()} hitSlop={10}>
          <Ionicons name="chevron-back" size={22} color={colors.textMuted} />
        </Pressable>

        <Image source={require('../assets/logo.png')} style={styles.logo} contentFit="contain" />

        <Text style={styles.title}>Set a new password</Text>
        <Text style={styles.sub}>
          We sent a code to{'\n'}
          <Text style={styles.email}>{email}</Text>
        </Text>

        <Text style={styles.label}>Reset code</Text>
        <TextInput
          style={styles.codeInput}
          value={code}
          onChangeText={(v) => setCode(v.replace(/[^0-9]/g, '').slice(0, 6))}
          keyboardType="number-pad"
          placeholder="______"
          placeholderTextColor={colors.textLight}
          maxLength={6}
          textAlign="center"
        />

        <Text style={styles.label}>New password (min 8 characters)</Text>
        <View style={styles.inputWrap}>
          <TextInput
            style={styles.input}
            value={password}
            onChangeText={setPassword}
            secureTextEntry={!showPw}
            placeholder="••••••••"
            placeholderTextColor={colors.textLight}
          />
          <Pressable onPress={() => setShowPw((s) => !s)} hitSlop={8}>
            <Ionicons name={showPw ? 'eye-off-outline' : 'eye-outline'} size={18} color={colors.textLight} />
          </Pressable>
        </View>

        {error && <Text style={styles.error}>{error}</Text>}
        {info && <Text style={styles.infoText}>{info}</Text>}

        <Pressable style={[styles.button, (!valid || busy) && { opacity: 0.5 }]} onPress={onSubmit} disabled={!valid || busy}>
          {busy ? <ActivityIndicator color={colors.white} /> : <Text style={styles.buttonText}>Reset password</Text>}
        </Pressable>

        <Pressable onPress={onResend} disabled={resending} style={styles.linkWrap}>
          <Text style={styles.link}>{resending ? 'Sending…' : "Didn't get a code? Resend"}</Text>
        </Pressable>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.xl },
  back: { alignSelf: 'flex-start', padding: spacing.xs },
  logo: { width: 200, height: 46, alignSelf: 'center', marginTop: spacing.lg },
  title: { fontSize: 24, fontWeight: '800', color: colors.text, textAlign: 'center', marginTop: spacing.xl },
  sub: { fontSize: 14, color: colors.textMuted, textAlign: 'center', marginTop: spacing.sm, lineHeight: 20 },
  email: { color: colors.text, fontWeight: '700' },
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginTop: spacing.lg, marginBottom: 6 },
  codeInput: {
    borderWidth: 1.5,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingVertical: spacing.md,
    fontSize: 26,
    letterSpacing: 8,
    color: colors.text,
    backgroundColor: colors.surface,
  },
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
  error: { color: colors.danger, fontSize: 13, marginTop: spacing.md, textAlign: 'center' },
  infoText: { color: colors.success, fontSize: 13, marginTop: spacing.md, textAlign: 'center' },
  button: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.xl,
  },
  buttonText: { color: colors.white, fontWeight: '800', fontSize: 16, textAlign: 'center', paddingHorizontal: 2 },
  linkWrap: { alignItems: 'center', marginTop: spacing.lg },
  link: { color: colors.primary, fontWeight: '600', fontSize: 14 },
});
