import { create } from 'zustand';
import AsyncStorage from '@react-native-async-storage/async-storage';
import type { TutorCard } from '../types';

const KEY = 'tc_favorites';

interface FavState {
  favorites: TutorCard[];
  hydrated: boolean;
  hydrate: () => Promise<void>;
  isFavorite: (id: number) => boolean;
  toggle: (tutor: TutorCard) => void;
}

// Favorites live ON THE DEVICE — parents/students have no accounts, so there is
// no server-side per-user favorites list. We persist the full card so the Saved
// screen renders offline.
export const useFavorites = create<FavState>((set, get) => ({
  favorites: [],
  hydrated: false,

  hydrate: async () => {
    try {
      const raw = await AsyncStorage.getItem(KEY);
      set({ favorites: raw ? JSON.parse(raw) : [], hydrated: true });
    } catch {
      set({ hydrated: true });
    }
  },

  isFavorite: (id) => get().favorites.some((t) => t.id === id),

  toggle: (tutor) => {
    const exists = get().favorites.some((t) => t.id === tutor.id);
    const favorites = exists
      ? get().favorites.filter((t) => t.id !== tutor.id)
      : [tutor, ...get().favorites];
    set({ favorites });
    AsyncStorage.setItem(KEY, JSON.stringify(favorites)).catch(() => {});
  },
}));
