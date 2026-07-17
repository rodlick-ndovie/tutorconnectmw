import { useCallback, useRef, useState } from 'react';

/**
 * Pull-to-refresh helper. Pass the refetch functions for whatever the screen
 * shows; they run in parallel and the spinner clears once they all settle.
 *
 *   const { refreshing, onRefresh } = useRefresh(q.refetch, other.refetch);
 *   <ScrollView refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}>
 *
 * `onRefresh` is referentially STABLE. It previously used the rest array as the
 * useCallback dependency list, which is a hooks-rules violation: the array is
 * new on every render, so the callback was rebuilt every render and the
 * <RefreshControl> element was re-created with it — which can swallow the
 * scroll gesture on Android. We keep the latest refetchers in a ref instead.
 */
export function useRefresh(...refetchers: Array<() => Promise<unknown> | unknown>) {
  const [refreshing, setRefreshing] = useState(false);

  // Always call the newest refetchers without making the callback unstable.
  const latest = useRef(refetchers);
  latest.current = refetchers;

  const onRefresh = useCallback(async () => {
    setRefreshing(true);
    try {
      await Promise.all(latest.current.map((fn) => Promise.resolve(fn())));
    } catch {
      // Errors already surface via each query's own error state.
    } finally {
      setRefreshing(false);
    }
  }, []);

  return { refreshing, onRefresh };
}
