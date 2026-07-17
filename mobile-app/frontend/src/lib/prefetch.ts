import type { QueryClient } from '@tanstack/react-query';
import { Image } from 'expo-image';
import { tutorsApi, metaApi, meApi, noticesApi } from '../api/endpoints';
import type { TutorCard } from '../types';

/**
 * Warm the query cache while the user is busy elsewhere (onboarding, the login
 * form, the splash gate) so the screens they land on render from cache instead
 * of showing a skeleton and a spinner.
 *
 * Everything here is best-effort: a failure must never block or break the UI,
 * so each prefetch swallows its own error. The screens still own their queries
 * and will refetch normally — this only front-loads the work.
 */

// Must match the page size Home asks for, or the cache key hits but the data
// would be refetched anyway.
const HOME_SECTION_SIZE = 5;

/** Avatars are the slow part: JSON is ~3KB, but each photo is a separate request. */
function prefetchAvatars(tutors: TutorCard[] | undefined) {
  const urls = (tutors ?? []).map((t) => t.profilePicture).filter((u): u is string => !!u);
  if (urls.length) Image.prefetch(urls, { cachePolicy: 'memory-disk' }).catch(() => {});
}

/**
 * Public data — safe to load for signed-out visitors too. This is what Home,
 * Search and the register form need.
 */
export async function prefetchPublicData(qc: QueryClient) {
  const warm = <T>(key: unknown[], fn: () => Promise<T>) =>
    qc.prefetchQuery({ queryKey: key, queryFn: fn }).catch(() => {});

  await Promise.allSettled([
    // The two tutor sections on Home, plus their photos.
    qc
      .prefetchQuery({
        queryKey: ['tutors', 'top-rated'],
        queryFn: () => tutorsApi.search({ sort: 'rating' }, 1, HOME_SECTION_SIZE),
      })
      .then(() => prefetchAvatars(qc.getQueryData<{ items: TutorCard[] }>(['tutors', 'top-rated'])?.items))
      .catch(() => {}),
    qc
      .prefetchQuery({
        queryKey: ['tutors', 'experienced'],
        queryFn: () => tutorsApi.search({ sort: 'experience' }, 1, HOME_SECTION_SIZE),
      })
      .then(() => prefetchAvatars(qc.getQueryData<{ items: TutorCard[] }>(['tutors', 'experienced'])?.items))
      .catch(() => {}),

    // Filter dropdowns (Search, Register, Post a Request) — small and rarely change.
    warm(['meta', 'districts'], metaApi.districts),
    warm(['meta', 'curricula'], metaApi.curricula),

    // Notices, reachable from the Home quick-actions grid.
    warm(['notices'], () => noticesApi.list()),
  ]);
}

/** Tutor-only data. Requires a restored session, so this runs after bootstrap. */
export async function prefetchTutorData(qc: QueryClient) {
  const warm = <T>(key: unknown[], fn: () => Promise<T>) =>
    qc.prefetchQuery({ queryKey: key, queryFn: fn }).catch(() => {});

  await Promise.allSettled([
    warm(['me', 'profile'], meApi.profile),
    warm(['me', 'subscription'], meApi.subscription),
    warm(['me', 'analytics'], meApi.analytics),
  ]);
}
