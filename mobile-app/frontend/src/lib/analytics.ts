import { useEffect, useRef } from 'react';
import { usePathname } from 'expo-router';
import { addBreadcrumb } from './monitoring';

/**
 * Lightweight analytics facade. Today it logs in dev and drops a Sentry
 * breadcrumb (useful context on crashes). Swap the body of `emit` for a real
 * provider (PostHog / Firebase / Amplitude) — every call site already flows
 * through here, so nothing else changes.
 */
function emit(event: string, props?: Record<string, unknown>) {
  if (__DEV__) console.log('[analytics]', event, props ?? '');
  addBreadcrumb(`analytics:${event}`, props);
  // TODO: forward to your analytics SDK here, e.g. posthog.capture(event, props)
}

export const analytics = {
  track: (event: string, props?: Record<string, unknown>) => emit(event, props),
  screen: (name: string) => emit('screen_view', { screen: name }),
};

// Common funnel events — use these constants so names stay consistent.
export const Events = {
  SignUpStarted: 'sign_up_started',
  SignUpCompleted: 'sign_up_completed',
  Login: 'login',
  PasswordResetRequested: 'password_reset_requested',
  TutorViewed: 'tutor_viewed',
  TutorContacted: 'tutor_contacted',
  ReviewSubmitted: 'review_submitted',
  PaperPurchaseStarted: 'paper_purchase_started',
  SubscriptionCheckout: 'subscription_checkout',
  ParentRequestPosted: 'parent_request_posted',
  LectureRequested: 'lecture_requested',
} as const;

/** Auto-fires a screen_view whenever the route path changes. */
export function useScreenTracking() {
  const pathname = usePathname();
  const last = useRef<string | null>(null);

  useEffect(() => {
    if (pathname && pathname !== last.current) {
      last.current = pathname;
      analytics.screen(pathname);
    }
  }, [pathname]);
}
