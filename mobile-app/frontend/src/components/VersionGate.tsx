import { View, Text, StyleSheet, Pressable, Linking, Platform } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import Constants from 'expo-constants';
import { useQuery } from '@tanstack/react-query';
import { api, unwrap } from '../api/client';
import { colors, radius, spacing } from '../theme/colors';

type AppConfig = {
  minVersion: string;
  latestVersion: string;
  androidStoreUrl: string;
  iosStoreUrl: string;
  message: string;
};

/** Compare dotted versions: returns true if `a` < `b`. */
function isOlder(a: string, b: string): boolean {
  const pa = a.split('.').map((n) => parseInt(n, 10) || 0);
  const pb = b.split('.').map((n) => parseInt(n, 10) || 0);
  for (let i = 0; i < Math.max(pa.length, pb.length); i++) {
    const x = pa[i] ?? 0;
    const y = pb[i] ?? 0;
    if (x !== y) return x < y;
  }
  return false;
}

/**
 * Blocks the app with a full-screen "update required" prompt when the installed
 * version is older than the server's `minVersion`. Fails open — if the config
 * can't be fetched, the app is shown normally.
 */
export function VersionGate({ children }: { children: React.ReactNode }) {
  const current = Constants.expoConfig?.version ?? '0.0.0';

  const config = useQuery({
    queryKey: ['app-config'],
    queryFn: () => unwrap<AppConfig>(api.get('/app-config')),
    staleTime: 30 * 60 * 1000,
    retry: 1,
  });

  const cfg = config.data;
  const mustUpdate = !!cfg && isOlder(current, cfg.minVersion);

  if (!mustUpdate) return <>{children}</>;

  const storeUrl = Platform.OS === 'ios' ? cfg!.iosStoreUrl : cfg!.androidStoreUrl;

  return (
    <View style={styles.wrap}>
      <View style={styles.icon}>
        <Ionicons name="rocket" size={40} color={colors.primary} />
      </View>
      <Text style={styles.title}>Update required</Text>
      <Text style={styles.body}>
        {cfg!.message ||
          'A newer version of TutorConnect Malawi is available. Please update to continue.'}
      </Text>
      <Text style={styles.version}>
        You have v{current} · minimum v{cfg!.minVersion}
      </Text>
      {storeUrl ? (
        <Pressable style={styles.button} onPress={() => Linking.openURL(storeUrl)}>
          <Text style={styles.buttonText}>Update now</Text>
        </Pressable>
      ) : null}
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: {
    flex: 1,
    backgroundColor: colors.bg,
    alignItems: 'center',
    justifyContent: 'center',
    padding: spacing.xl,
    gap: spacing.md,
  },
  icon: {
    width: 84,
    height: 84,
    borderRadius: 42,
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  title: { fontSize: 24, fontWeight: '800', color: colors.text, marginTop: spacing.sm },
  body: { fontSize: 15, color: colors.textMuted, textAlign: 'center', lineHeight: 22 },
  version: { fontSize: 12, color: colors.textLight, marginTop: spacing.sm },
  button: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
    paddingHorizontal: spacing.xxl,
    marginTop: spacing.lg,
  },
  buttonText: { color: colors.white, fontWeight: '800', fontSize: 16 },
});
