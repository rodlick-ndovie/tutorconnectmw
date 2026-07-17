import { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  FlatList,
  StyleSheet,
  Pressable,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useLocalSearchParams, useRouter } from 'expo-router';
import { universityApi } from '../../src/api/endpoints';
import { ErrorState } from '../../src/components/ErrorState';
import { ListFooter } from '../../src/components/ListFooter';
import { SkeletonList } from '../../src/components/skeletons';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useInfiniteList } from '../../src/hooks/useInfiniteList';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { UniAccountType, UniTutor } from '../../src/types';

export default function UniversityDirectory() {
  const router = useRouter();
  const { type } = useLocalSearchParams<{ type?: string }>();
  const [tab, setTab] = useState<UniAccountType>(type === 'firm' ? 'firm' : 'individual');
  const [q, setQ] = useState('');

  const list = useInfiniteList(
    ['university', 'tutors', tab, q],
    (page) => universityApi.tutors({ accountType: tab, q: q || undefined }, page, 20),
    { keepPrevious: true }
  );
  const { refreshing, onRefresh } = useRefresh(list.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'University & College Support', headerShown: true }} />

      <View style={styles.segment}>
        <Seg
          label="Tutors"
          icon="school"
          active={tab === 'individual'}
          onPress={() => setTab('individual')}
        />
        <Seg
          label="Companies"
          icon="business"
          active={tab === 'firm'}
          onPress={() => setTab('firm')}
        />
      </View>

      <View style={styles.searchBar}>
        <Ionicons name="search" size={18} color={colors.textLight} />
        <TextInput
          style={styles.input}
          placeholder={tab === 'firm' ? 'Search companies' : 'Search tutors'}
          placeholderTextColor={colors.textLight}
          value={q}
          onChangeText={setQ}
        />
        {q.length > 0 && (
          <Pressable onPress={() => setQ('')} hitSlop={8}>
            <Ionicons name="close-circle" size={18} color={colors.textLight} />
          </Pressable>
        )}
      </View>

      {list.isLoading ? (
        <View style={styles.list}>
          <SkeletonList count={5} />
        </View>
      ) : list.isError ? (
        <ErrorState message={(list.error as Error)?.message} onRetry={() => list.refetch()} />
      ) : (
        <FlatList
          data={list.items}
          keyExtractor={(t) => String(t.id)}
          contentContainerStyle={styles.list}
          onEndReached={list.loadMore}
          onEndReachedThreshold={0.5}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
          renderItem={({ item }) => (
            <UniCard tutor={item} onPress={() => router.push(`/university/${item.id}`)} />
          )}
          ListFooterComponent={
            <ListFooter loadingMore={list.loadingMore} hasMore={list.hasMore} count={list.items.length} />
          }
          ListEmptyComponent={
            <View style={styles.empty}>
              <Ionicons
                name={tab === 'firm' ? 'business-outline' : 'school-outline'}
                size={44}
                color={colors.textLight}
              />
              <Text style={styles.emptyTitle}>
                No {tab === 'firm' ? 'companies' : 'tutors'} yet
              </Text>
              <Text style={styles.emptyHint}>
                Approved {tab === 'firm' ? 'companies' : 'university tutors'} will appear here.
              </Text>
            </View>
          }
        />
      )}

      <View style={styles.footer}>
        <Pressable style={styles.footerBtn} onPress={() => router.push('/university/request-lecture')}>
          <Ionicons name="easel" size={18} color={colors.white} />
          <Text style={styles.footerText}>Request a Lecture</Text>
        </Pressable>
        <Pressable style={styles.footerAlt} onPress={() => router.push('/university/register')}>
          <Text style={styles.footerAltText}>Join</Text>
        </Pressable>
      </View>
    </SafeAreaView>
  );
}

function UniCard({ tutor, onPress }: { tutor: UniTutor; onPress: () => void }) {
  const isFirm = tutor.accountType === 'firm';
  return (
    <Pressable style={styles.card} onPress={onPress}>
      <View style={styles.cardHead}>
        {tutor.profilePicture ? (
          <Image source={{ uri: tutor.profilePicture }} style={styles.avatar} contentFit="cover" />
        ) : (
          <View style={[styles.avatar, styles.avatarFallback]}>
            <Ionicons name={isFirm ? 'business' : 'person'} size={22} color={colors.primary} />
          </View>
        )}
        <View style={{ flex: 1 }}>
          <Text style={styles.name} numberOfLines={1}>
            {tutor.name}
          </Text>
          <Text style={styles.meta} numberOfLines={1}>
            {[tutor.cityLocation, tutor.teachingMode].filter(Boolean).join(' · ')}
          </Text>
          <View style={styles.badges}>
            <View style={[styles.badge, isFirm && styles.badgeFirm]}>
              <Text style={[styles.badgeText, isFirm && styles.badgeTextFirm]}>
                {isFirm ? 'Company' : 'Tutor'}
              </Text>
            </View>
            {tutor.serviceAreas.slice(0, 1).map((s) => (
              <View key={s} style={styles.badge}>
                <Text style={styles.badgeText} numberOfLines={1}>
                  {s}
                </Text>
              </View>
            ))}
          </View>
        </View>
        <Ionicons name="chevron-forward" size={18} color={colors.textLight} />
      </View>

      {tutor.specializations.length > 0 && (
        <Text style={styles.specs} numberOfLines={1}>
          {tutor.specializations.slice(0, 4).join(' • ')}
        </Text>
      )}
    </Pressable>
  );
}

function Seg({
  label,
  icon,
  active,
  onPress,
}: {
  label: string;
  icon: keyof typeof Ionicons.glyphMap;
  active: boolean;
  onPress: () => void;
}) {
  return (
    <Pressable style={[styles.seg, active && styles.segActive]} onPress={onPress}>
      <Ionicons name={icon} size={16} color={active ? colors.white : colors.textMuted} />
      <Text style={[styles.segText, active && styles.segTextActive]}>{label}</Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  segment: { flexDirection: 'row', gap: spacing.sm, padding: spacing.lg, paddingBottom: spacing.sm },
  seg: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 6,
    paddingVertical: spacing.md,
    borderRadius: radius.pill,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
  },
  segActive: { backgroundColor: colors.primary, borderColor: colors.primary },
  segText: { fontWeight: '700', color: colors.textMuted, fontSize: 14 },
  segTextActive: { color: colors.white },

  searchBar: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    marginHorizontal: spacing.lg,
    marginBottom: spacing.sm,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    borderRadius: radius.md,
    borderWidth: 1,
    borderColor: colors.border,
    backgroundColor: colors.surface,
  },
  input: { flex: 1, fontSize: 15, color: colors.text },

  list: { padding: spacing.lg, gap: spacing.md, paddingBottom: spacing.xxl },
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
    gap: spacing.sm,
  },
  cardHead: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  avatar: { width: 52, height: 52, borderRadius: 26, backgroundColor: colors.surface },
  avatarFallback: { alignItems: 'center', justifyContent: 'center', backgroundColor: colors.primaryBg },
  name: { fontSize: 16, fontWeight: '700', color: colors.text },
  meta: { fontSize: 12, color: colors.textMuted, marginTop: 1 },
  badges: { flexDirection: 'row', gap: spacing.xs, marginTop: 6 },
  badge: {
    backgroundColor: colors.primaryBg,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: radius.pill,
    maxWidth: 150,
  },
  badgeFirm: { backgroundColor: '#EDE9FE' },
  badgeText: { fontSize: 10, fontWeight: '800', color: colors.primaryDark },
  badgeTextFirm: { color: '#6D28D9' },
  specs: { fontSize: 12, color: colors.textMuted },

  empty: { alignItems: 'center', marginTop: spacing.xxl * 2, gap: spacing.sm },
  emptyTitle: { fontSize: 16, fontWeight: '700', color: colors.text },
  emptyHint: { fontSize: 13, color: colors.textMuted, textAlign: 'center', paddingHorizontal: spacing.xl },

  footer: {
    flexDirection: 'row',
    gap: spacing.sm,
    padding: spacing.lg,
    borderTopWidth: 1,
    borderTopColor: colors.border,
    backgroundColor: colors.white,
  },
  footerBtn: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
  },
  footerText: { color: colors.white, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
  footerAlt: {
    paddingHorizontal: spacing.xl,
    borderRadius: radius.pill,
    borderWidth: 1.5,
    borderColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
  },
  footerAltText: { color: colors.primary, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
});
