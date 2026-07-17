import { useInfiniteQuery, keepPreviousData, type QueryKey } from '@tanstack/react-query';

type Page<T> = { items: T[]; meta?: { total?: number; page?: number; limit?: number } };

/**
 * Cursor-free infinite pagination for our `{ items, meta:{page,limit,total} }`
 * list endpoints. Give it a stable key and a `fetchPage(page)` function; it
 * returns the flattened `items`, the `total`, and `loadMore`/`loadingMore`
 * helpers to wire into a FlatList's onEndReached + footer.
 */
export function useInfiniteList<T>(
  key: QueryKey,
  fetchPage: (page: number) => Promise<Page<T>>,
  opts: { enabled?: boolean; keepPrevious?: boolean } = {}
) {
  const query = useInfiniteQuery({
    queryKey: key,
    queryFn: ({ pageParam }) => fetchPage(pageParam),
    initialPageParam: 1,
    getNextPageParam: (lastPage, allPages) => {
      const limit = lastPage.meta?.limit ?? lastPage.items.length ?? 20;
      const total = lastPage.meta?.total;
      const loaded = allPages.reduce((n, p) => n + p.items.length, 0);
      // If the API reports a total, use it; otherwise stop when a short page arrives.
      if (total != null) return loaded < total ? allPages.length + 1 : undefined;
      return lastPage.items.length >= limit ? allPages.length + 1 : undefined;
    },
    enabled: opts.enabled,
    placeholderData: opts.keepPrevious ? keepPreviousData : undefined,
  });

  const items = query.data?.pages.flatMap((p) => p.items) ?? [];
  const total = query.data?.pages[0]?.meta?.total ?? items.length;

  const loadMore = () => {
    if (query.hasNextPage && !query.isFetchingNextPage) query.fetchNextPage();
  };

  return {
    items,
    total,
    isLoading: query.isLoading,
    isError: query.isError,
    error: query.error,
    refetch: query.refetch,
    loadMore,
    loadingMore: query.isFetchingNextPage,
    hasMore: !!query.hasNextPage,
  };
}
