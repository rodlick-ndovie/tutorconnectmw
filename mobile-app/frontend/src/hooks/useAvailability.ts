import { useEffect, useState } from 'react';
import { authApi } from '../api/endpoints';

export type AvailabilityState =
  | { status: 'idle' }
  | { status: 'checking' }
  | { status: 'available'; message: string }
  | { status: 'taken'; message: string };

type Field = 'email' | 'username' | 'phone';

/**
 * Debounced "is this free?" check against /auth/check-availability.
 * Stays idle until the value looks complete enough to be worth asking about.
 */
export function useAvailability(field: Field, value: string, isValid: boolean): AvailabilityState {
  const [state, setState] = useState<AvailabilityState>({ status: 'idle' });

  useEffect(() => {
    const v = value.trim();
    if (!v || !isValid) {
      setState({ status: 'idle' });
      return;
    }

    let cancelled = false;
    setState({ status: 'checking' });

    const t = setTimeout(async () => {
      try {
        const res = await authApi.checkAvailability({ [field]: v });
        const result = res[field];
        if (cancelled || !result) return;
        setState(
          result.available
            ? { status: 'available', message: result.message }
            : { status: 'taken', message: result.message }
        );
      } catch {
        if (!cancelled) setState({ status: 'idle' }); // network hiccup: don't block the user
      }
    }, 500);

    return () => {
      cancelled = true;
      clearTimeout(t);
    };
  }, [field, value, isValid]);

  return state;
}
