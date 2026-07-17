import { View, Text, FlatList, StyleSheet, Pressable, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { noticesApi } from '../src/api/endpoints';
import { ErrorState } from '../src/components/ErrorState';
import { SkeletonList, ListRowSkeleton } from '../src/components/skeletons';
import { useRefresh } from '../src/hooks/useRefresh';
import { colors, radius, spacing } from '../src/theme/colors';

export default function Notices() {
  const router = useRouter();
  const q = useQuery({ queryKey: ['notices'], queryFn: () => noticesApi.list() });
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Notices', headerShown: true }} />
      {q.isLoading ? (
        <View style={styles.list}>
          <SkeletonList count={6} Item={ListRowSkeleton} gap={spacing.md} />
        </View>
      ) : q.isError ? (
        <ErrorState message={(q.error as Error)?.message} onRetry={() => q.refetch()} />
      ) : (
        <FlatList
          data={q.data ?? []}
          keyExtractor={(n) => String(n.id)}
          contentContainerStyle={styles.list}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
          renderItem={({ item }) => (
            <Pressable style={styles.card} onPress={() => router.push(`/notice/${item.id}`)}>
              <View style={styles.head}>
                <View style={styles.iconWrap}>
                  <Ionicons name="megaphone" size={18} color={colors.primary} />
                </View>
                <View style={{ flex: 1 }}>
                  <Text style={styles.school} numberOfLines={1}>
                    {item.schoolName}
                  </Text>
                  <Text style={styles.type}>{item.noticeType}</Text>
                </View>
                <Ionicons name="chevron-forward" size={18} color={colors.textLight} />
              </View>
              <Text style={styles.title} numberOfLines={2}>
                {item.title}
              </Text>
              <Text style={styles.body} numberOfLines={3}>
                {item.content}
              </Text>
              {item.image && (
                <Image source={{ uri: item.image }} style={styles.image} contentFit="cover" />
              )}
            </Pressable>
          )}
          ListEmptyComponent={
            <View style={styles.empty}>
              <Ionicons name="megaphone-outline" size={44} color={colors.textLight} />
              <Text style={styles.emptyTitle}>No notices yet</Text>
            </View>
          }
        />
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  list: { padding: spacing.lg, gap: spacing.md, paddingBottom: spacing.xxl },
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
    gap: spacing.sm,
  },
  head: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  iconWrap: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  school: { fontSize: 14, fontWeight: '700', color: colors.text },
  type: { fontSize: 11, color: colors.textMuted },
  title: { fontSize: 15, fontWeight: '700', color: colors.text },
  body: { fontSize: 13, color: colors.textMuted, lineHeight: 19 },
  image: { width: '100%', height: 150, borderRadius: radius.md },
  empty: { alignItems: 'center', marginTop: spacing.xxl * 2, gap: spacing.sm },
  emptyTitle: { fontSize: 16, fontWeight: '700', color: colors.text },
});
