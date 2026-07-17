import { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  StyleSheet,
  Pressable,
  ScrollView,
  ActivityIndicator,
  Platform,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useAuth } from '../src/store/auth';
import { useKeyboardHeight } from '../src/hooks/useKeyboardHeight';
import { colors, radius, spacing } from '../src/theme/colors';

export default function Login() {
  const router = useRouter();
  const { login, status } = useAuth();
  const [identifier, setIdentifier] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [busy, setBusy] = useState(false);
  const keyboardHeight = useKeyboardHeight();

  const onSubmit = async () => {
    setError(null);
    setBusy(true);
    try {
      await login(identifier.trim(), password);
      router.replace('/(tabs)/profile');
    } catch (e) {
      setError((e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['top', 'bottom']}>
      <Stack.Screen options={{ headerShown: false }} />
        <ScrollView
          contentContainerStyle={[styles.content, { paddingBottom: spacing.xxl + keyboardHeight }]}
          keyboardShouldPersistTaps="handled"
          keyboardDismissMode="on-drag"
        >
          <Pressable style={styles.close} onPress={() => router.replace('/(tabs)')} hitSlop={10}>
            <Ionicons name="close" size={22} color={colors.textMuted} />
          </Pressable>

          <Image source={require('../assets/logo.png')} style={styles.logo} contentFit="contain" />

          <Text style={styles.title}>Welcome back</Text>
          <Text style={styles.subtitle}>
            Log in to manage your tutor profile, subjects and subscription.
          </Text>

          <View style={styles.form}>
            <Text style={styles.label}>Username or Email</Text>
            <View style={styles.inputWrap}>
              <Ionicons name="person-outline" size={18} color={colors.textLight} />
              <TextInput
                style={styles.input}
                autoCapitalize="none"
                keyboardType="email-address"
                placeholder="you@example.com"
                placeholderTextColor={colors.textLight}
                value={identifier}
                onChangeText={setIdentifier}
              />
            </View>

            <Text style={styles.label}>Password</Text>
            <View style={styles.inputWrap}>
              <Ionicons name="lock-closed-outline" size={18} color={colors.textLight} />
              <TextInput
                style={styles.input}
                secureTextEntry={!showPassword}
                placeholder="••••••••"
                placeholderTextColor={colors.textLight}
                value={password}
                onChangeText={setPassword}
              />
              <Pressable onPress={() => setShowPassword((s) => !s)} hitSlop={8}>
                <Ionicons
                  name={showPassword ? 'eye-off-outline' : 'eye-outline'}
                  size={18}
                  color={colors.textLight}
                />
              </Pressable>
            </View>

            {error && <Text style={styles.error}>{error}</Text>}

            <Pressable
              style={[styles.button, (busy || !identifier || !password) && { opacity: 0.6 }]}
              onPress={onSubmit}
              disabled={busy || status === 'loading' || !identifier || !password}
            >
              {busy ? (
                <ActivityIndicator color={colors.white} />
              ) : (
                <Text style={styles.buttonText}>Log In</Text>
              )}
            </Pressable>

            <Pressable style={styles.forgot} onPress={() => router.push('/forgot-password')} hitSlop={8}>
              <Text style={styles.forgotText}>Forgot password?</Text>
            </Pressable>
          </View>

          <View style={styles.divider}>
            <View style={styles.line} />
            <Text style={styles.dividerText}>new here?</Text>
            <View style={styles.line} />
          </View>

          <Pressable style={styles.secondaryBtn} onPress={() => router.push('/register')}>
            <Text style={styles.secondaryText}>Create a tutor account</Text>
          </Pressable>

          <Text style={styles.footNote}>
            Students don't need an account — just browse and contact tutors.
          </Text>
        </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.xl, flexGrow: 1 },
  close: { alignSelf: 'flex-end', padding: spacing.xs },
  logo: { width: 200, height: 46, alignSelf: 'center', marginTop: spacing.lg },
  title: {
    fontSize: 26,
    fontWeight: '800',
    color: colors.text,
    textAlign: 'center',
    marginTop: spacing.xl,
  },
  subtitle: {
    fontSize: 14,
    color: colors.textMuted,
    textAlign: 'center',
    marginTop: spacing.sm,
    lineHeight: 20,
  },
  form: { marginTop: spacing.xl },
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginBottom: 6 },
  inputWrap: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: Platform.OS === 'ios' ? spacing.md : 2,
    backgroundColor: colors.surface,
    marginBottom: spacing.md,
  },
  input: { flex: 1, fontSize: 15, color: colors.text, paddingVertical: spacing.md },
  error: { color: colors.danger, fontSize: 13, marginBottom: spacing.sm },
  button: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.sm,
  },
  // alignSelf:'stretch' + textAlign:'center' stops the child Text from being
  // shrink-wrapped, which on Android under-measures bold text and clips the tail
  // ("Log In" -> "Log"). The text now fills the button and centers itself.
  buttonText: { color: colors.white, fontWeight: '800', fontSize: 16, textAlign: 'center', alignSelf: 'stretch' },
  forgot: { alignSelf: 'center', marginTop: spacing.md },
  forgotText: { color: colors.primary, fontWeight: '600', fontSize: 14 },
  divider: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    marginTop: spacing.xl,
  },
  line: { flex: 1, height: 1, backgroundColor: colors.border },
  dividerText: { color: colors.textLight, fontSize: 12, fontWeight: '600' },
  secondaryBtn: {
    borderWidth: 1.5,
    borderColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
    alignItems: 'center',
    marginTop: spacing.lg,
  },
  secondaryText: { color: colors.primary, fontWeight: '700', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
  footNote: {
    fontSize: 12,
    color: colors.textLight,
    textAlign: 'center',
    marginTop: spacing.xl,
  },
});
