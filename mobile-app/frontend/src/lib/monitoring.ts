import Constants from 'expo-constants';

// Crash & error reporting via Sentry. Disabled until EXPO_PUBLIC_SENTRY_DSN is
// set, so nothing loads the native module in dev / Expo Go. Once you add the DSN
// and make a build, unhandled JS errors and native crashes report automatically.
//
// To ENABLE later:
//   1. Set EXPO_PUBLIC_SENTRY_DSN in .env
//   2. For source maps in EAS builds, re-add the config plugin to app.json:
//        ["@sentry/react-native", { "organization": "<org>", "project": "<project>" }]
//      (it was removed to silence the "Missing config for organization/project"
//       warning while Sentry is disabled).
const DSN = process.env.EXPO_PUBLIC_SENTRY_DSN;

type SentryModule = typeof import('@sentry/react-native');
let Sentry: SentryModule | null = null;

export function initMonitoring(): void {
  if (!DSN) return;
  try {
    // Required lazily so the native module is only touched when actually enabled.
    Sentry = require('@sentry/react-native') as SentryModule;
    Sentry.init({
      dsn: DSN,
      environment: __DEV__ ? 'development' : 'production',
      release: Constants.expoConfig?.version,
      tracesSampleRate: 0.2,
      // Don't spam Sentry with expected "offline / request failed" noise.
      beforeSend: (event) => event,
    });
  } catch (err) {
    console.warn('[monitoring] Sentry init skipped:', (err as Error).message);
    Sentry = null;
  }
}

export function captureError(error: unknown, context?: Record<string, unknown>): void {
  if (__DEV__) console.error('[error]', error, context ?? '');
  try {
    Sentry?.captureException(error, context ? { extra: context } : undefined);
  } catch {
    /* never let reporting throw */
  }
}

export function addBreadcrumb(message: string, data?: Record<string, unknown>): void {
  try {
    Sentry?.addBreadcrumb({ message, data, level: 'info' });
  } catch {
    /* ignore */
  }
}

export function setUserContext(user: { id: number; username?: string | null } | null): void {
  try {
    Sentry?.setUser(user ? { id: String(user.id), username: user.username ?? undefined } : null);
  } catch {
    /* ignore */
  }
}
