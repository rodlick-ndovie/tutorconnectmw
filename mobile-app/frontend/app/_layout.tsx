import { useEffect } from 'react';
import { Stack } from 'expo-router';
import { QueryClient, QueryClientProvider, onlineManager } from '@tanstack/react-query';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { StatusBar } from 'expo-status-bar';
import NetInfo from '@react-native-community/netinfo';
import { useAuth } from '../src/store/auth';
import { useFavorites } from '../src/store/favorites';
import { setAuthFailureHandler } from '../src/api/client';
import { OfflineBanner } from '../src/components/OfflineBanner';
import { VersionGate } from '../src/components/VersionGate';
import { usePushNavigation } from '../src/lib/push';
import { initMonitoring, setUserContext } from '../src/lib/monitoring';
import { useScreenTracking } from '../src/lib/analytics';
import { prefetchPublicData, prefetchTutorData } from '../src/lib/prefetch';

// Start crash reporting as early as possible (no-op until a DSN is configured).
initMonitoring();

// Let TanStack Query know about connectivity so it pauses fetches while offline
// and auto-refetches the moment the connection returns.
onlineManager.setEventListener((setOnline) =>
  NetInfo.addEventListener((state) => setOnline(!!state.isConnected))
);

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      retry: 1,
      staleTime: 60_000,
      gcTime: 24 * 60 * 60 * 1000, // keep cached data a day so offline screens still render
    },
  },
});

export default function RootLayout() {
  const bootstrap = useAuth((s) => s.bootstrap);
  const logout = useAuth((s) => s.logout);
  const user = useAuth((s) => s.user);
  const hydrateFavorites = useFavorites((s) => s.hydrate);

  // Route the user to the right screen when they tap a push notification.
  usePushNavigation();
  // Auto-log screen views for analytics.
  useScreenTracking();

  // Attach the signed-in tutor to crash reports (helps debug their issues).
  useEffect(() => {
    setUserContext(user ? { id: user.id, username: user.username } : null);
  }, [user]);

  // The app is PUBLIC-first: no login required to browse and find tutors.
  // We silently restore a tutor/admin session if one exists, and load the
  // on-device favorites. There is no auth gate.
  useEffect(() => {
    setAuthFailureHandler(() => logout());
    bootstrap();
    hydrateFavorites();

    // Start loading Home's data NOW, in parallel with the splash gate and the
    // onboarding slides. By the time the user swipes through and lands on the
    // tabs, the cache is warm and the screen renders instantly instead of
    // showing skeletons. Best-effort: never blocks or breaks the UI.
    prefetchPublicData(queryClient);
  }, []);

  // Tutor-only data needs a session, so it waits for bootstrap to restore one.
  useEffect(() => {
    if (user) prefetchTutorData(queryClient);
  }, [user?.id]);

  return (
    // expo-router requires react-native-gesture-handler, and it must be rooted
    // here or touch/gesture handling misbehaves app-wide. It was missing entirely.
    <GestureHandlerRootView style={{ flex: 1 }}>
    <SafeAreaProvider>
      <QueryClientProvider client={queryClient}>
        <StatusBar style="dark" />
        <VersionGate>
        <Stack screenOptions={{ headerShown: false }}>
          <Stack.Screen name="index" />
          <Stack.Screen name="onboarding" />
          <Stack.Screen name="(tabs)" />
          {/* Auth screens are full-screen: no header, no tab bar. */}
          <Stack.Screen name="login" options={{ headerShown: false }} />
          <Stack.Screen name="register" options={{ headerShown: false }} />
          <Stack.Screen name="verify-otp" options={{ headerShown: false }} />
          <Stack.Screen name="forgot-password" options={{ headerShown: false }} />
          <Stack.Screen name="reset-password" options={{ headerShown: false }} />
          <Stack.Screen name="tutor/[id]" options={{ headerShown: true, title: 'Tutor' }} />
          <Stack.Screen name="notice/[id]" options={{ headerShown: true, title: 'Notice' }} />
          <Stack.Screen name="resources/index" options={{ headerShown: true, title: 'Resources' }} />
          <Stack.Screen name="resources/checkout" options={{ headerShown: true, title: 'Payment' }} />
          {/* University & College support */}
          <Stack.Screen name="university/index" options={{ headerShown: true, title: 'University & College' }} />
          <Stack.Screen name="university/[id]" options={{ headerShown: true }} />
          <Stack.Screen name="university/register" options={{ headerShown: true }} />
          <Stack.Screen name="university/request-lecture" options={{ headerShown: true }} />
          <Stack.Screen name="university/portal" options={{ headerShown: true }} />
          <Stack.Screen name="parent-requests/index" options={{ headerShown: true, title: 'Request a Tutor' }} />
          <Stack.Screen name="parent-requests/new" options={{ headerShown: true, title: 'Post a Request' }} />
          <Stack.Screen name="notices" options={{ headerShown: true, title: 'Notices' }} />
          <Stack.Screen name="dashboard/edit-profile" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/change-password" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/enquiries" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/availability" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/subjects" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/analytics" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/subscription" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/resubmit" options={{ headerShown: true }} />
          <Stack.Screen name="dashboard/checkout" options={{ headerShown: true }} />
          <Stack.Screen name="notifications" options={{ headerShown: true }} />
        </Stack>
        </VersionGate>
        {/* Only in production builds: in dev (Expo Go over LAN) NetInfo misreports
            connectivity, so the banner would show a false "No internet". */}
        {!__DEV__ && <OfflineBanner />}
      </QueryClientProvider>
    </SafeAreaProvider>
    </GestureHandlerRootView>
  );
}
