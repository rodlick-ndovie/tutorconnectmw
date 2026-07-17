import { View, Text, FlatList, StyleSheet, Pressable, Linking, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack } from 'expo-router';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { meApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useInfiniteList } from '../../src/hooks/useInfiniteList';
import { ListFooter } from '../../src/components/ListFooter';
import { SkeletonList, ListRowSkeleton } from '../../src/components/skeletons';
import { toIntlMw } from '../../src/utils/phone';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { Enquiry } from '../../src/types';

export default function Enquiries() {
  const qc = useQueryClient();
  const q = useInfiniteList<Enquiry>(['me', 'enquiries'], async (page) => {
    const res = await meApi.enquiries(page);
    return { items: res.items, meta: { total: res.total, page: res.page, limit: res.limit } };
  });

  const markRead = useMutation({
    mutationFn: (id: number) => meApi.markEnquiryRead(id),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['me', 'enquiries'] }),
  });

  const { refreshing, onRefresh } = useRefresh(q.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Messages', headerShown: true }} />
      {q.isLoading ? (
        <View style={styles.list}>
          <SkeletonList count={6} Item={ListRowSkeleton} gap={spacing.md} />
        </View>
      ) : (
        <FlatList
          data={q.items}
          keyExtractor={(e) => String(e.id)}
          contentContainerStyle={styles.list}
          onEndReached={q.loadMore}
          onEndReachedThreshold={0.5}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
          ListFooterComponent={<ListFooter loadingMore={q.loadingMore} hasMore={q.hasMore} count={q.items.length} />}
          renderItem={({ item }) => (
            <EnquiryCard item={item} onSeen={() => !item.isRead && markRead.mutate(item.id)} />
          )}
          ListEmptyComponent={
            <View style={styles.empty}>
              <Ionicons name="mail-open-outline" size={48} color={colors.textLight} />
              <Text style={styles.emptyTitle}>No messages yet</Text>
              <Text style={styles.emptyText}>
                When someone contacts you from your profile, their message appears here.
              </Text>
            </View>
          }
        />
      )}
    </SafeAreaView>
  );
}

function EnquiryCard({ item, onSeen }: { item: Enquiry; onSeen: () => void }) {
  const wa = toIntlMw(item.senderPhone);
  const tel = toIntlMw(item.senderPhone);

  return (
    <Pressable style={[styles.card, !item.isRead && styles.cardUnread]} onPress={onSeen}>
      <View style={styles.cardHead}>
        <View style={{ flex: 1 }}>
          <Text style={styles.sender}>{item.senderName}</Text>
          <Text style={styles.subject}>{item.subject}</Text>
        </View>
        {!item.isRead && <View style={styles.dot} />}
      </View>

      <Text style={styles.message}>{item.message}</Text>

      {/* Read-only: the tutor doesn't reply in-app, they reach the sender directly. */}
      <View style={styles.actions}>
        {wa && (
          <Action icon="logo-whatsapp" label="WhatsApp" color={colors.success} onPress={() => Linking.openURL(`https://wa.me/${wa}`)} />
        )}
        {tel && (
          <Action icon="call" label="Call" color={colors.accent} onPress={() => Linking.openURL(`tel:+${tel}`)} />
        )}
        {item.senderEmail && (
          <Action icon="mail" label="Email" color={colors.primary} onPress={() => Linking.openURL(`mailto:${item.senderEmail}`)} />
        )}
      </View>

      <View style={styles.metaRow}>
        <Text style={styles.meta}>{item.createdAt?.slice(0, 16).replace('T', ' ')}</Text>
        {!item.emailSent && <Text style={styles.metaWarn}>· email pending</Text>}
      </View>
    </Pressable>
  );
}

function Action({
  icon,
  label,
  color,
  onPress,
}: {
  icon: keyof typeof Ionicons.glyphMap;
  label: string;
  color: string;
  onPress: () => void;
}) {
  return (
    <Pressable style={styles.action} onPress={onPress}>
      <Ionicons name={icon} size={16} color={color} />
      <Text style={[styles.actionText, { color }]}>{label}</Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  list: { padding: spacing.lg, gap: spacing.md, flexGrow: 1 },
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
  },
  cardUnread: { borderColor: colors.primary, backgroundColor: colors.primaryBg },
  cardHead: { flexDirection: 'row', alignItems: 'flex-start', gap: spacing.sm },
  sender: { fontSize: 15, fontWeight: '800', color: colors.text },
  subject: { fontSize: 14, fontWeight: '600', color: colors.primaryDark, marginTop: 1 },
  dot: { width: 10, height: 10, borderRadius: 5, backgroundColor: colors.primary, marginTop: 4 },
  message: { fontSize: 14, color: colors.text, lineHeight: 20, marginTop: spacing.sm },
  actions: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginTop: spacing.md },
  action: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    paddingHorizontal: spacing.md,
    paddingVertical: 8,
    borderRadius: radius.pill,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
  },
  actionText: { fontSize: 13, fontWeight: '700' },
  metaRow: { flexDirection: 'row', gap: 6, marginTop: spacing.sm },
  meta: { fontSize: 11, color: colors.textLight },
  metaWarn: { fontSize: 11, color: colors.textMuted, fontWeight: '600' },
  empty: { alignItems: 'center', marginTop: spacing.xxl * 2, gap: spacing.sm, paddingHorizontal: spacing.xl },
  emptyTitle: { fontSize: 16, fontWeight: '700', color: colors.text },
  emptyText: { fontSize: 14, color: colors.textMuted, textAlign: 'center' },
});
