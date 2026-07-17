import { AxiosError } from 'axios';
import { create } from 'zustand';
import { authApi, type RegisterBody } from '../api/endpoints';
import { tokenStore, userCache } from '../api/tokenStore';
import { registerForPush } from '../lib/push';
import { analytics, Events } from '../lib/analytics';
import type { AuthUser } from '../types';

/** Did the server actually reject us (vs. we just couldn't reach it)? */
function isAuthRejection(err: unknown): boolean {
  const status = (err as AxiosError)?.response?.status;
  return status === 401 || status === 403;
}

interface AuthState {
  user: AuthUser | null;
  status: 'loading' | 'authenticated' | 'unauthenticated';
  bootstrap: () => Promise<void>;
  login: (login: string, password: string) => Promise<void>;
  register: (body: RegisterBody) => Promise<{ email: string; message: string }>;
  verifyOtp: (email: string, code: string) => Promise<void>;
  logout: () => Promise<void>;
}

export const useAuth = create<AuthState>((set) => ({
  user: null,
  status: 'loading',

  /**
   * Restore the session on launch. The session must survive app restarts and a
   * flaky network — the user should only ever be logged out by tapping Log Out,
   * or if the server explicitly rejects the token.
   */
  bootstrap: async () => {
    const access = await tokenStore.load();
    if (!access) return set({ status: 'unauthenticated', user: null });

    // 1. Show the cached user immediately — no network wait, works offline.
    const cached = await userCache.get<AuthUser>();
    if (cached) set({ user: cached, status: 'authenticated' });

    // 2. Revalidate in the background.
    try {
      const user = await authApi.me();
      await userCache.set(user);
      set({ user, status: 'authenticated' });
      registerForPush().catch(() => {});
    } catch (err) {
      if (isAuthRejection(err)) {
        // The server says this token is no longer valid — genuinely sign out.
        await tokenStore.clear();
        set({ status: 'unauthenticated', user: null });
        return;
      }
      // Network/server error: KEEP the session. Previously this branch cleared
      // the tokens, so a brief outage silently logged the user out on launch.
      if (cached) {
        set({ user: cached, status: 'authenticated' });
        registerForPush().catch(() => {});
      } else {
        // Tokens exist but we've never cached a user and can't reach the API.
        // Stay signed in optimistically; screens will retry their own queries.
        set({ status: 'authenticated' });
      }
    }
  },

  login: async (login, password) => {
    const { user, accessToken, refreshToken } = await authApi.login(login, password);
    await tokenStore.set(accessToken, refreshToken);
    await userCache.set(user); // so the session restores instantly next launch
    set({ user, status: 'authenticated' });
    analytics.track(Events.Login, { role: user.role });
    registerForPush().catch(() => {});
  },

  // Create a tutor account. Returns which email to verify (no session yet).
  register: async (body) => authApi.register(body),

  // Confirm the OTP → logs the new tutor in.
  verifyOtp: async (email, code) => {
    const { user, accessToken, refreshToken } = await authApi.verifyOtp(email, code);
    await tokenStore.set(accessToken, refreshToken);
    await userCache.set(user);
    set({ user, status: 'authenticated' });
    analytics.track(Events.SignUpCompleted, { role: user.role });
    registerForPush().catch(() => {});
  },

  // The ONLY path that ends a session (besides the server rejecting the token).
  logout: async () => {
    const refresh = await tokenStore.getRefresh();
    if (refresh) await authApi.logout(refresh).catch(() => {});
    await tokenStore.clear(); // also clears the cached user
    set({ user: null, status: 'unauthenticated' });
  },
}));
