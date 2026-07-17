import AsyncStorage from '@react-native-async-storage/async-storage';

const KEY = 'onboarding_seen_v1';

export async function hasSeenOnboarding(): Promise<boolean> {
  try {
    return (await AsyncStorage.getItem(KEY)) === '1';
  } catch {
    return false;
  }
}

export async function markOnboardingSeen(): Promise<void> {
  try {
    await AsyncStorage.setItem(KEY, '1');
  } catch {
    // best-effort; worst case the intro shows again next launch
  }
}
