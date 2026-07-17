import * as SecureStore from 'expo-secure-store';
import AsyncStorage from '@react-native-async-storage/async-storage';

const ACCESS = 'tc_access';
const REFRESH = 'tc_refresh';
const USER = 'tc_user';

// In-memory cache so the axios interceptor avoids an async read on every call.
let accessToken: string | null = null;

/**
 * The last signed-in user, cached on the device. Lets us restore the session
 * instantly on launch (and offline) instead of blocking on /auth/me — and
 * crucially, without logging the user out when the network is briefly down.
 */
export const userCache = {
  async get<T>(): Promise<T | null> {
    try {
      const raw = await AsyncStorage.getItem(USER);
      return raw ? (JSON.parse(raw) as T) : null;
    } catch {
      return null;
    }
  },
  async set(user: unknown) {
    try {
      await AsyncStorage.setItem(USER, JSON.stringify(user));
    } catch {
      /* best effort */
    }
  },
  async clear() {
    try {
      await AsyncStorage.removeItem(USER);
    } catch {
      /* best effort */
    }
  },
};

export const tokenStore = {
  getAccess: () => accessToken,
  async load() {
    accessToken = await SecureStore.getItemAsync(ACCESS);
    return accessToken;
  },
  async getRefresh() {
    return SecureStore.getItemAsync(REFRESH);
  },
  async set(access: string, refresh: string) {
    accessToken = access;
    await SecureStore.setItemAsync(ACCESS, access);
    await SecureStore.setItemAsync(REFRESH, refresh);
  },
  async setAccess(access: string) {
    accessToken = access;
    await SecureStore.setItemAsync(ACCESS, access);
  },
  async clear() {
    accessToken = null;
    await SecureStore.deleteItemAsync(ACCESS);
    await SecureStore.deleteItemAsync(REFRESH);
    await userCache.clear();
  },
};
