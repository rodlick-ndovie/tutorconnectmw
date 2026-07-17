import { Text, FlatList, StyleSheet, Pressable, View, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack } from 'expo-router';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { notificationsApi } from '../src/api/endpoints';
import { useRefresh } from '../src/hooks/useRefresh';
import { useInfiniteList } from '../src/hooks/useInfiniteList';
import { ListFooter } from '../src/components/ListFooter';
import { SkeletonList, ListRowSkeleton } from '../src/components/skeletons';
import { colors, radius, spacing } from '../src/theme/colors';
import type { NotificationItem } from '../src/types';

const ICONS: Record<string, keyof typeof Ionicons.glyphMap> = {
  tutor_approved: 'checkmark-circle',
  tutor_rejected: 'alert-circle',
  payment_verified: 'card',
  inquiry: 'mail',
  message: 'chatbubble',
};

export default function Notifications() {
  const qc = useQueryClient();
  const q = useInfiniteList(['notifications'], (page) => notificationsApi.list(page));

  const markRead = useMutation({
    mutationFn: (id: number) => notificationsApi.markRead(id),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['notifications'] }),
  });
  const markAll = useMutation({
    mutationFn: () => notificationsApi.markAllRead(),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['notifications'] }),
  });

  const unread = q.items.filter((n) => !n.read).length;
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen
        options={{
          title: 'Notifications',
          headerShown: true,
          headerRight: () =>
            unread > 0 ? (
              <Pressable onPress={() => markAll.mutate()} hitSlop={8}>
                <Text style={styles.markAll}>Mark all</Text>
              </Pressable>
            ) : null,
        }}
      />
      {q.isLoading ? (
        <View style={styles.list}>
          <SkeletonList count={7} Item={ListRowSkeleton} gap={spacing.sm} />
        </View>
      ) : (
        <FlatList
          data={q.items}
          keyExtractor={(n) => String(n.id)}
          contentContainerStyle={styles.list}
          onEndReached={q.loadMore}
          onEndReachedThreshold={0.5}
          ListFooterComponent={
            <ListFooter loadingMore={q.loadingMore} hasMore={q.hasMore} count={q.items.length} />
          }
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
          renderItem={({ item }) => (
            <Pressable
              style={[styles.row, !item.read && styles.unreadRow]}
              onPress={() => !item.read && markRead.mutate(item.id)}
            >
              <View style={styles.iconBox}>
                <Ionicons name={ICONS[item.type] ?? 'notifications'} size={20} color={colors.primary} />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.title}>{item.title}</Text>
                {item.body && <Text style={styles.body}>{item.body}</Text>}
                <Text style={styles.time}>{item.createdAt?.slice(0, 16).replace('T', ' ')}</Text>
              </View>
              {!item.read && <View style={styles.dot} />}
            </Pressable>
          )}
          ListEmptyComponent={
            <View style={styles.empty}>
              <Ionicons name="notifications-off-outline" size={48} color={colors.textLight} />
              <Text style={styles.emptyText}>No notifications yet</Text>
            </View>
          }
        />
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  markAll: { color: colors.primary, fontWeight: '700', marginRight: spacing.md },
  list: { padding: spacing.lg, gap: spacing.sm },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
  },
  unreadRow: { backgroundColor: '#EEF2FF', borderColor: colors.primary },
  iconBox: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: colors.surface,
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: { fontSize: 15, fontWeight: '700', color: colors.text },
  body: { fontSize: 13, color: colors.textMuted, marginTop: 2 },
  time: { fontSize: 11, color: colors.textLight, marginTop: 4 },
  dot: { width: 10, height: 10, borderRadius: 5, backgroundColor: colors.primary },
  empty: { alignItems: 'center', marginTop: spacing.xxl * 2, gap: spacing.sm },
  emptyText: { fontSize: 16, fontWeight: '700', color: colors.text },
});
