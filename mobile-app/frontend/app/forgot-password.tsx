import { useState } from 'react';
import { View, Text, TextInput, StyleSheet, Pressable, ActivityIndicator, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { authApi } from '../src/api/endpoints';
import { colors, radius, spacing } from '../src/theme/colors';

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export default function ForgotPassword() {
  const router = useRouter();
  const [email, setEmail] = useState('');
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const onSubmit = async () => {
    setError(null);
    setBusy(true);
    try {
      await authApi.forgotPassword(email.trim());
      router.replace({ pathname: '/reset-password', params: { email: email.trim() } });
    } catch (e) {
      setError((e as Error).message);
    } finally {
      setBusy(false);
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

        <Text style={styles.title}>Forgot password?</Text>
        <Text style={styles.sub}>
          Enter the email on your account and we'll send you a 6-digit reset code.
        </Text>

        <Text style={styles.label}>Email</Text>
        <View style={styles.inputWrap}>
          <Ionicons name="mail-outline" size={18} color={colors.textLight} />
          <TextInput
            style={styles.input}
            value={email}
            onChangeText={setEmail}
            keyboardType="email-address"
            autoCapitalize="none"
            placeholder="you@example.com"
            placeholderTextColor={colors.textLight}
          />
        </View>

        {error && <Text style={styles.error}>{error}</Text>}

        <Pressable
          style={[styles.button, (!EMAIL_RE.test(email.trim()) || busy) && { opacity: 0.5 }]}
          onPress={onSubmit}
          disabled={!EMAIL_RE.test(email.trim()) || busy}
        >
          {busy ? <ActivityIndicator color={colors.white} /> : <Text style={styles.buttonText}>Send reset code</Text>}
        </Pressable>

        <Pressable onPress={() => router.replace('/login')} style={styles.linkWrap}>
          <Text style={styles.link}>Back to log in</Text>
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
  title: { fontSize: 26, fontWeight: '800', color: colors.text, textAlign: 'center', marginTop: spacing.xl },
  sub: { fontSize: 14, color: colors.textMuted, textAlign: 'center', marginTop: spacing.sm, lineHeight: 20 },
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginTop: spacing.xl, marginBottom: 6 },
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
  error: { color: colors.danger, fontSize: 13, marginTop: spacing.sm },
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
