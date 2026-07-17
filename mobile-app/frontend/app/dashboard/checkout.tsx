import { useRef, useState } from 'react';
import { View, Text, StyleSheet, ActivityIndicator, Pressable } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Stack, useLocalSearchParams, useRouter } from 'expo-router';
import { WebView, type WebViewNavigation } from 'react-native-webview';
import { useQueryClient } from '@tanstack/react-query';
import { Ionicons } from '@expo/vector-icons';
import { paymentsApi } from '../../src/api/endpoints';
import { colors, radius, spacing } from '../../src/theme/colors';

// Hosts the PayChangu hosted-checkout page in a WebView. When PayChangu
// redirects to our return URL (the tutorconnect:// deep link) we stop, verify
// the payment server-side, and show the result.
export default function Checkout() {
  const { url, txRef } = useLocalSearchParams<{ url: string; txRef: string }>();
  const router = useRouter();
  const qc = useQueryClient();
  const [state, setState] = useState<'paying' | 'verifying' | 'done' | 'failed'>('paying');
  const verified = useRef(false);

  const verify = async () => {
    if (verified.current) return;
    verified.current = true;
    setState('verifying');
    try {
      const res = await paymentsApi.status(txRef);
      if (res.status === 'verified') {
        qc.invalidateQueries({ queryKey: ['me', 'subscription'] });
        setState('done');
      } else {
        setState('failed');
      }
    } catch {
      setState('failed');
    }
  };

  // PayChangu sends the browser to return_url when the flow ends.
  const onNav = (nav: WebViewNavigation) => {
    if (nav.url?.startsWith('tutorconnect://') || nav.url?.includes('/payments/return')) {
      verify();
    }
  };

  if (state !== 'paying') {
    return (
      <SafeAreaView style={styles.center}>
        <Stack.Screen options={{ title: 'Payment', headerShown: true }} />
        {state === 'verifying' ? (
          <>
            <ActivityIndicator size="large" color={colors.primary} />
            <Text style={styles.msg}>Confirming your payment…</Text>
          </>
        ) : state === 'done' ? (
          <>
            <Ionicons name="checkmark-circle" size={64} color={colors.success} />
            <Text style={styles.msg}>Payment confirmed — your plan is active.</Text>
            <Pressable style={styles.btn} onPress={() => router.replace('/dashboard/subscription')}>
              <Text style={styles.btnText}>Done</Text>
            </Pressable>
          </>
        ) : (
          <>
            <Ionicons name="alert-circle" size={64} color={colors.danger} />
            <Text style={styles.msg}>We couldn't confirm the payment yet.</Text>
            <Pressable style={styles.btn} onPress={verify}>
              <Text style={styles.btnText}>Check again</Text>
            </Pressable>
            <Pressable onPress={() => router.back()}>
              <Text style={styles.link}>Back to plans</Text>
            </Pressable>
          </>
        )}
      </SafeAreaView>
    );
  }

  return (
    <View style={styles.flex}>
      <Stack.Screen options={{ title: 'Complete Payment', headerShown: true }} />
      <WebView
        source={{ uri: url }}
        onNavigationStateChange={onNav}
        onShouldStartLoadWithRequest={(req) => {
          if (req.url.startsWith('tutorconnect://')) {
            verify();
            return false;
          }
          return true;
        }}
        startInLoadingState
        renderLoading={() => (
          <View style={styles.center}>
            <ActivityIndicator size="large" color={colors.primary} />
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  flex: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg, gap: spacing.md, padding: spacing.xl },
  msg: { fontSize: 16, color: colors.text, textAlign: 'center' },
  btn: { backgroundColor: colors.primary, borderRadius: radius.pill, paddingVertical: spacing.md, paddingHorizontal: spacing.xxl, marginTop: spacing.sm },
  btnText: { color: colors.white, fontWeight: '700', fontSize: 16 },
  link: { color: colors.textMuted, marginTop: spacing.sm },
});
