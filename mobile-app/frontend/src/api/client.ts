import axios, { AxiosError, AxiosRequestConfig } from 'axios';
import Constants from 'expo-constants';
import { tokenStore } from './tokenStore';
import type { ApiEnvelope } from '../types';

// 4100 matches the backend's PORT. It was moved off 4000 because another local
// project also binds 4000 and Windows silently allows the double-bind.
const API_PORT = process.env.EXPO_PUBLIC_API_PORT || '4100';
const API_PATH = '/api/v1';

/**
 * Resolve the API base URL.
 *
 * In development we derive the host from the Metro dev-server URI (e.g.
 * "10.127.153.239:8081") so a phone on the LAN reaches the API automatically —
 * no need to edit app.json every time the network/IP changes.
 *
 * Order: explicit env override → Metro dev host → app.json extra.apiUrl
 * (production builds, where there is no dev server) → localhost.
 */
function resolveApiUrl(): string {
  const explicit = process.env.EXPO_PUBLIC_API_URL;
  if (explicit) return explicit;

  // Metro/Expo Go exposes the dev server host; shape differs across SDKs.
  const hostUri =
    Constants.expoConfig?.hostUri ??
    (Constants.expoGoConfig as { debuggerHost?: string } | undefined)?.debuggerHost;

  const host = typeof hostUri === 'string' ? hostUri.split(':')[0] : undefined;
  if (host) return `http://${host}:${API_PORT}${API_PATH}`;

  const configured = Constants.expoConfig?.extra?.apiUrl as string | undefined;
  return configured || `http://localhost:${API_PORT}${API_PATH}`;
}

const apiUrl = resolveApiUrl();
if (__DEV__) console.log('[api] base URL:', apiUrl);

// 30s: registration and file uploads can be slow on a mobile connection, and a
// timeout surfaces to the user as a confusing "Network Error".
export const api = axios.create({ baseURL: apiUrl, timeout: 30000 });

// Attach the access token to every request.
api.interceptors.request.use((config) => {
  const token = tokenStore.getAccess();
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// Silent refresh on 401: try once, replay the original request, else log out.
let refreshing: Promise<RefreshResult> | null = null;
let onAuthFailure: (() => void) | null = null;
export const setAuthFailureHandler = (fn: () => void) => {
  onAuthFailure = fn;
};

/**
 * Refreshing must distinguish "the refresh token is genuinely invalid" from
 * "we couldn't reach the server". Previously both returned null and the caller
 * wiped the session — so a brief network blip silently logged the user out.
 */
type RefreshResult =
  | { kind: 'ok'; token: string }
  | { kind: 'invalid' } // server rejected the refresh token — session is really over
  | { kind: 'network' }; // couldn't reach the server — KEEP the session

async function refreshAccessToken(): Promise<RefreshResult> {
  const refreshToken = await tokenStore.getRefresh();
  if (!refreshToken) return { kind: 'invalid' };
  try {
    const res = await axios.post<ApiEnvelope<{ accessToken: string; refreshToken: string }>>(
      `${apiUrl}/auth/refresh`,
      { refreshToken },
      { timeout: 20000 }
    );
    const { accessToken, refreshToken: newRefresh } = res.data.data;
    await tokenStore.set(accessToken, newRefresh);
    return { kind: 'ok', token: accessToken };
  } catch (err) {
    const e = err as AxiosError;
    // A response means the server spoke: only 401/403 invalidates the session.
    if (e.response) {
      const s = e.response.status;
      return s === 401 || s === 403 ? { kind: 'invalid' } : { kind: 'network' };
    }
    // No response at all (offline, timeout, DNS) — do NOT destroy the session.
    return { kind: 'network' };
  }
}

/**
 * A request that never got a response at all — the connection itself failed.
 * On a cold app launch the phone's Wi-Fi radio is often still waking up, so the
 * first TCP connect fails and axios reports a bare "Network Error". The server
 * never saw the request, which is why retrying the very same call succeeds.
 */
function isTransientNetworkError(error: AxiosError) {
  return !error.response && error.code !== 'ERR_CANCELED';
}

// Backoff between connection retries. Short: this is a radio/DHCP hiccup, not
// a busy server, so it clears in well under a second.
const RETRY_DELAYS_MS = [400, 1200];
const sleep = (ms: number) => new Promise((r) => setTimeout(r, ms));

api.interceptors.response.use(
  (res) => res,
  async (error: AxiosError) => {
    const req = error.config as (AxiosRequestConfig & { _netRetry?: number }) | undefined;

    // Retry connection failures before anything else. Safe even for POSTs
    // (login, uploads): no response means the request never reached the server,
    // so it cannot have been applied twice.
    if (req && isTransientNetworkError(error)) {
      const attempt = req._netRetry ?? 0;
      if (attempt < RETRY_DELAYS_MS.length) {
        req._netRetry = attempt + 1;
        await sleep(RETRY_DELAYS_MS[attempt]);
        return api(req);
      }
    }

    const original = error.config as AxiosRequestConfig & { _retry?: boolean };
    if (error.response?.status === 401 && original && !original._retry) {
      original._retry = true;
      refreshing = refreshing || refreshAccessToken();
      const result = await refreshing;
      refreshing = null;

      if (result.kind === 'ok') {
        original.headers = { ...original.headers, Authorization: `Bearer ${result.token}` };
        return api(original);
      }
      // Only log out when the server actually rejected our refresh token.
      // On a network failure we keep the tokens so the session survives.
      if (result.kind === 'invalid') {
        await tokenStore.clear();
        onAuthFailure?.();
      }
    }
    return Promise.reject(error);
  }
);

/** Unwrap the API envelope and surface a friendly message on error. */
export async function unwrap<T>(promise: Promise<{ data: ApiEnvelope<T> }>): Promise<T> {
  try {
    const res = await promise;
    return res.data.data;
  } catch (err) {
    const e = err as AxiosError<ApiEnvelope<unknown>>;

    if (isTransientNetworkError(e)) {
      // Axios says only "Network Error", which tells the user nothing and told
      // us nothing while debugging. Log where it actually failed.
      if (__DEV__) {
        console.warn(`[api] could not reach ${e.config?.baseURL ?? ''}${e.config?.url ?? ''} (${e.code})`);
      }
      throw new Error("Can't reach the server. Check your connection and try again.");
    }

    const message = e.response?.data?.error?.message || e.message || 'Request failed';
    throw new Error(message);
  }
}

export function getMeta(res: { data: ApiEnvelope<unknown> }) {
  return res.data.meta;
}
