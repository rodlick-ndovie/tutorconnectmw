import { useEffect, useRef, useState } from 'react';
import { Animated, Text, StyleSheet, Platform } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import NetInfo from '@react-native-community/netinfo';
import { colors, spacing } from '../theme/colors';

/**
 * A slim banner that slides down from the top whenever the device loses its
 * connection. Cached data (TanStack Query) keeps showing underneath, so the app
 * stays usable offline; this just tells the user why nothing is updating.
 */
export function OfflineBanner() {
  const [offline, setOffline] = useState(false);
  const slide = useRef(new Animated.Value(-60)).current;

  useEffect(() => {
    const unsub = NetInfo.addEventListener((state) => {
      // Only flag a DEFINITE disconnection (no network interface). We deliberately
      // ignore `isInternetReachable`: NetInfo probes a public endpoint to set it,
      // which returns false on captive portals, slow links, and LAN-only dev
      // networks — producing a false "No internet" banner even when connected.
      setOffline(state.isConnected === false);
    });
    return () => unsub();
  }, []);

  useEffect(() => {
    Animated.timing(slide, {
      toValue: offline ? 0 : -60,
      duration: 220,
      useNativeDriver: true,
    }).start();
  }, [offline, slide]);

  return (
    <Animated.View
      pointerEvents="none"
      style={[styles.wrap, { transform: [{ translateY: slide }] }]}
    >
      <SafeAreaView edges={['top']}>
        <Text style={styles.text}>
          <Ionicons name="cloud-offline" size={13} color={colors.white} /> No internet connection
        </Text>
      </SafeAreaView>
    </Animated.View>
  );
}

const styles = StyleSheet.create({
  wrap: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    backgroundColor: colors.secondary,
    zIndex: 1000,
    ...(Platform.OS === 'android' ? { elevation: 8 } : {}),
  },
  text: {
    color: colors.white,
    fontSize: 13,
    fontWeight: '700',
    textAlign: 'center',
    paddingVertical: spacing.sm,
  },
});
