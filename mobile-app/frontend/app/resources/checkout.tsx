import { useRef, useState } from 'react';
import { View, Text, StyleSheet, ActivityIndicator, Pressable, Linking } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Stack, useLocalSearchParams, useRouter } from 'expo-router';
import { WebView, type WebViewNavigation } from 'react-native-webview';
import { Ionicons } from '@expo/vector-icons';
import { resourcesApi } from '../../src/api/endpoints';
import { colors, radius, spacing } from '../../src/theme/colors';

// Hosts the PayChangu checkout for a paid past paper. On return we verify the
// purchase server-side; once paid we expose the download link.
export default function ResourceCheckout() {
  const { url, txRef } = useLocalSearchParams<{ url: string; txRef: string }>();
  const router = useRouter();
  const [state, setState] = useState<'paying' | 'verifying' | 'done' | 'failed'>('paying');
  const [downloadUrl, setDownloadUrl] = useState<string | null>(null);
  const verified = useRef(false);

  const verify = async () => {
    if (verified.current) return;
    verified.current = true;
    setState('verifying');
    try {
      const res = await resourcesApi.purchaseStatus(txRef);
      if (res.status === 'paid' && res.downloadUrl) {
        setDownloadUrl(res.downloadUrl);
        setState('done');
      } else {
        verified.current = false; // allow a manual re-check
        setState('failed');
      }
    } catch {
      verified.current = false;
      setState('failed');
    }
  };

  const onNav = (nav: WebViewNavigation) => {
    if (nav.url?.startsWith('tutorconnect://') || nav.url?.includes('/payments/return')) verify();
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
            <Text style={styles.msg}>Payment confirmed. Your paper is ready.</Text>
            <Pressable style={styles.btn} onPress={() => downloadUrl && Linking.openURL(downloadUrl)}>
              <Ionicons name="download" size={18} color={colors.white} />
              <Text style={styles.btnText}>Download paper</Text>
            </Pressable>
            <Pressable onPress={() => router.back()}>
              <Text style={styles.link}>Back to resources</Text>
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
              <Text style={styles.link}>Back to resources</Text>
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
        source={{ uri: String(url) }}
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
  btn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
    paddingHorizontal: spacing.xxl,
    marginTop: spacing.sm,
  },
  btnText: { color: colors.white, fontWeight: '700', fontSize: 16 },
  link: { color: colors.textMuted, marginTop: spacing.sm },
});
