import { ScrollView, View, Text, StyleSheet, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { useLocalSearchParams, Stack } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { noticesApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { ErrorState } from '../../src/components/ErrorState';
import { ArticleSkeleton } from '../../src/components/skeletons';
import { colors, radius, spacing } from '../../src/theme/colors';

export default function NoticeDetail() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const noticeId = Number(id);
  const q = useQuery({ queryKey: ['notice', noticeId], queryFn: () => noticesApi.get(noticeId) });
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Notice', headerShown: true }} />
      {q.isLoading ? (
        <ArticleSkeleton />
      ) : q.isError || !q.data ? (
        <ErrorState message={(q.error as Error)?.message} onRetry={() => q.refetch()} />
      ) : (
        <ScrollView
          contentContainerStyle={styles.content}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
        >
          <View style={styles.head}>
            <View style={styles.iconWrap}>
              <Ionicons name="school" size={20} color={colors.primary} />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={styles.school}>{q.data.schoolName}</Text>
              <View style={styles.typePill}>
                <Text style={styles.typeText}>{q.data.noticeType}</Text>
              </View>
            </View>
          </View>

          <Text style={styles.title}>{q.data.title}</Text>
          {q.data.image && (
            <Image source={{ uri: q.data.image }} style={styles.image} contentFit="cover" />
          )}
          <Text style={styles.body}>{q.data.content}</Text>
        </ScrollView>
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.lg, paddingBottom: spacing.xxl },
  head: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  iconWrap: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: colors.primaryBg,
    justifyContent: 'center',
    alignItems: 'center',
  },
  school: { fontSize: 15, fontWeight: '700', color: colors.text },
  typePill: {
    alignSelf: 'flex-start',
    backgroundColor: colors.primaryBg,
    borderRadius: radius.pill,
    paddingHorizontal: 10,
    paddingVertical: 2,
    marginTop: 4,
  },
  typeText: { color: colors.primaryDark, fontSize: 11, fontWeight: '700' },
  title: { fontSize: 22, fontWeight: '800', color: colors.text, marginTop: spacing.lg },
  image: { width: '100%', height: 200, borderRadius: radius.lg, marginTop: spacing.lg },
  body: { fontSize: 15, color: colors.textMuted, lineHeight: 24, marginTop: spacing.lg },
});
