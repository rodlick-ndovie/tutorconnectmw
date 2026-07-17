import { useState } from 'react';
import { View, Text, TextInput, StyleSheet, Pressable, ActivityIndicator, ScrollView, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { meApi } from '../../src/api/endpoints';
import { tokenStore } from '../../src/api/tokenStore';
import { colors, radius, spacing } from '../../src/theme/colors';

export default function ChangePassword() {
  const router = useRouter();
  const [current, setCurrent] = useState('');
  const [next, setNext] = useState('');
  const [confirm, setConfirm] = useState('');
  const [showPw, setShowPw] = useState(false);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const tooShort = next.length > 0 && next.length < 8;
  const mismatch = confirm.length > 0 && next !== confirm;
  const valid = current.length > 0 && next.length >= 8 && next === confirm;

  const onSubmit = async () => {
    setError(null);
    setBusy(true);
    try {
      // The server revokes every session and hands back a fresh pair — store it
      // so this device stays logged in while other devices are signed out.
      const { accessToken, refreshToken } = await meApi.changePassword(current, next);
      await tokenStore.set(accessToken, refreshToken);
      Alert.alert('Password changed', 'Your password has been updated.', [
        { text: 'OK', onPress: () => router.back() },
      ]);
    } catch (e) {
      setError((e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Change Password', headerShown: true }} />
      <ScrollView contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <Text style={styles.intro}>
          Enter your current password, then choose a new one. You'll stay signed in on this device; any other devices will be signed out.
        </Text>

        <Field
          label="Current password"
          value={current}
          onChangeText={setCurrent}
          secure={!showPw}
        />

        <Field
          label="New password (min 8 characters)"
          value={next}
          onChangeText={setNext}
          secure={!showPw}
        />
        {tooShort && <Text style={styles.fieldError}>Must be at least 8 characters.</Text>}

        <Field
          label="Confirm new password"
          value={confirm}
          onChangeText={setConfirm}
          secure={!showPw}
        />
        {mismatch && <Text style={styles.fieldError}>Passwords don't match.</Text>}

        <Pressable style={styles.showRow} onPress={() => setShowPw((s) => !s)} hitSlop={8}>
          <Ionicons name={showPw ? 'eye-off-outline' : 'eye-outline'} size={18} color={colors.textMuted} />
          <Text style={styles.showText}>{showPw ? 'Hide passwords' : 'Show passwords'}</Text>
        </Pressable>

        {error && <Text style={styles.error}>{error}</Text>}

        <Pressable
          style={[styles.button, (!valid || busy) && { opacity: 0.5 }]}
          onPress={onSubmit}
          disabled={!valid || busy}
        >
          {busy ? <ActivityIndicator color={colors.white} /> : <Text style={styles.buttonText}>Change Password</Text>}
        </Pressable>
      </ScrollView>
    </SafeAreaView>
  );
}

function Field({
  label,
  value,
  onChangeText,
  secure,
}: {
  label: string;
  value: string;
  onChangeText: (v: string) => void;
  secure: boolean;
}) {
  return (
    <>
      <Text style={styles.label}>{label}</Text>
      <View style={styles.inputWrap}>
        <TextInput
          style={styles.input}
          value={value}
          onChangeText={onChangeText}
          secureTextEntry={secure}
          placeholder="••••••••"
          placeholderTextColor={colors.textLight}
          autoCapitalize="none"
          autoCorrect={false}
        />
      </View>
    </>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.xl },
  intro: { fontSize: 14, color: colors.textMuted, lineHeight: 20, marginBottom: spacing.md },
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginTop: spacing.lg, marginBottom: 6 },
  inputWrap: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    backgroundColor: colors.surface,
  },
  input: { flex: 1, fontSize: 15, color: colors.text, paddingVertical: spacing.md },
  fieldError: { color: colors.danger, fontSize: 12, marginTop: 6 },
  showRow: { flexDirection: 'row', alignItems: 'center', gap: 6, marginTop: spacing.lg },
  showText: { color: colors.textMuted, fontSize: 14, fontWeight: '600' },
  error: { color: colors.danger, fontSize: 13, marginTop: spacing.md, textAlign: 'center' },
  button: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.xl,
  },
  buttonText: { color: colors.white, fontWeight: '800', fontSize: 16, textAlign: 'center', paddingHorizontal: 2 },
});
