import { useEffect } from 'react';
import { Platform } from 'react-native';
import { useRouter, type Href } from 'expo-router';
import Constants, { ExecutionEnvironment } from 'expo-constants';
import { devicesApi } from '../api/endpoints';

// Remote push was removed from Expo Go (SDK 53+). Detect Expo Go and skip — the
// expo-notifications native side-effects throw there. Push works normally in a
// development build or a production (store) build.
const isExpoGo = Constants.executionEnvironment === ExecutionEnvironment.StoreClient;

/**
 * Ask for permission, get the Expo push token, and register it with the API.
 * Lazy-imports expo-notifications/expo-device so Expo Go never loads them.
 * Safe to call repeatedly; resolves to null when unavailable.
 */
export async function registerForPush(): Promise<string | null> {
  if (isExpoGo) return null;

  try {
    const Device = await import('expo-device');
    const Notifications = await import('expo-notifications');

    Notifications.setNotificationHandler({
      handleNotification: async () => ({
        shouldShowBanner: true,
        shouldShowList: true,
        shouldPlaySound: true,
        shouldSetBadge: false,
      }),
    });

    if (!Device.isDevice) return null;

    const existing = await Notifications.getPermissionsAsync();
    let status = existing.status;
    if (status !== 'granted') {
      status = (await Notifications.requestPermissionsAsync()).status;
    }
    if (status !== 'granted') return null;

    if (Platform.OS === 'android') {
      await Notifications.setNotificationChannelAsync('default', {
        name: 'default',
        importance: Notifications.AndroidImportance.DEFAULT,
      });
    }

    const projectId =
      Constants.expoConfig?.extra?.eas?.projectId ?? Constants.easConfig?.projectId;
    const token = (await Notifications.getExpoPushTokenAsync(projectId ? { projectId } : undefined)).data;
    await devicesApi.register(token, Platform.OS);
    return token;
  } catch (err) {
    console.warn('[push] registration skipped:', (err as Error).message);
    return null;
  }
}

/**
 * Turn a notification's `data` payload into an in-app route. The server sends a
 * `data` object; we accept an explicit `{ route }` or infer one from `type`/`id`.
 */
function routeForNotification(data: Record<string, unknown> | undefined): Href | null {
  if (!data) return '/notifications';
  if (typeof data.route === 'string') return data.route as Href;

  const id = data.id ?? data.entityId;
  switch (data.type) {
    case 'notice':
    case 'announcement':
      return id ? (`/notice/${id}` as Href) : '/notices';
    case 'review':
    case 'inquiry':
    case 'booking':
    case 'tutor_approved':
    case 'tutor_status':
      return '/(tabs)/profile';
    case 'lecture_request':
    case 'university':
      return '/university/portal';
    case 'parent_request':
      return '/parent-requests';
    case 'subscription':
    case 'subscription_expiry':
      return '/dashboard/subscription';
    default:
      return '/notifications';
  }
}

/**
 * Route the user to the relevant screen when they tap a push notification —
 * both while the app is running and when a tap cold-starts it. No-op in Expo Go
 * (remote push isn't delivered there).
 */
export function usePushNavigation() {
  const router = useRouter();

  useEffect(() => {
    if (isExpoGo) return;
    let sub: { remove: () => void } | undefined;
    let cancelled = false;

    (async () => {
      try {
        const Notifications = await import('expo-notifications');

        const go = (data: Record<string, unknown> | undefined) => {
          const route = routeForNotification(data);
          if (route) setTimeout(() => router.push(route), 0); // let navigation mount first
        };

        // Cold start: app was opened by tapping a notification.
        const initial = await Notifications.getLastNotificationResponseAsync();
        if (!cancelled && initial) {
          go(initial.notification.request.content.data as Record<string, unknown>);
        }

        // Warm: tapped while the app is running/backgrounded.
        sub = Notifications.addNotificationResponseReceivedListener((response) => {
          go(response.notification.request.content.data as Record<string, unknown>);
        });
      } catch (err) {
        console.warn('[push] navigation listener skipped:', (err as Error).message);
      }
    })();

    return () => {
      cancelled = true;
      sub?.remove();
    };
  }, [router]);
}
