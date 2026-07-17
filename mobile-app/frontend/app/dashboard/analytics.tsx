import { ScrollView, View, Text, StyleSheet, ActivityIndicator, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { meApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { StatGridSkeleton } from '../../src/components/skeletons';
import { colors, radius, spacing } from '../../src/theme/colors';

export default function Analytics() {
  const q = useQuery({ queryKey: ['me', 'analytics'], queryFn: meApi.analytics });
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Analytics', headerShown: true }} />
      {q.isLoading ? (
        <StatGridSkeleton count={5} />
      ) : q.isError ? (
        <Text style={styles.error}>{(q.error as Error).message}</Text>
      ) : (
        <ScrollView
          contentContainerStyle={styles.grid}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
          }
        >
          <Card icon="eye" label="Profile Views" value={q.data!.profileViews} />
          <Card icon="call" label="Contact Clicks" value={q.data!.contactClicks} />
          <Card icon="heart" label="Saved By" value={q.data!.favorites} />
          <Card icon="star" label="Rating" value={q.data!.rating} />
          <Card icon="chatbox" label="Reviews" value={q.data!.reviewCount} />
        </ScrollView>
      )}
    </SafeAreaView>
  );
}

function Card({ icon, label, value }: { icon: keyof typeof Ionicons.glyphMap; label: string; value: number }) {
  return (
    <View style={styles.card}>
      <Ionicons name={icon} size={26} color={colors.primary} />
      <Text style={styles.value}>{value}</Text>
      <Text style={styles.label}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  error: { color: colors.danger, padding: spacing.lg },
  grid: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.md, padding: spacing.lg },
  card: {
    width: '47%',
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.lg,
    alignItems: 'center',
    gap: spacing.xs,
  },
  value: { fontSize: 28, fontWeight: '800', color: colors.text },
  label: { fontSize: 13, color: colors.textMuted },
});
