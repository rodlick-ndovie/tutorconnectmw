import { Text, FlatList, StyleSheet, View, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useFavorites } from '../../src/store/favorites';
import { useRefresh } from '../../src/hooks/useRefresh';
import { TutorCard } from '../../src/components/TutorCard';
import { colors, spacing } from '../../src/theme/colors';

export default function Saved() {
  const favorites = useFavorites((s) => s.favorites);
  // Favorites live on the device — refreshing re-reads them from storage.
  const hydrate = useFavorites((s) => s.hydrate);
  const { refreshing, onRefresh } = useRefresh(hydrate);

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <Text style={styles.heading}>Saved Tutors</Text>
      <FlatList
        data={favorites}
        keyExtractor={(t) => String(t.id)}
        contentContainerStyle={styles.list}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
        renderItem={({ item }) => <TutorCard tutor={item} />}
        ListEmptyComponent={
          <View style={styles.empty}>
            <Ionicons name="heart-outline" size={48} color={colors.textLight} />
            <Text style={styles.emptyText}>No saved tutors yet</Text>
            <Text style={styles.emptyHint}>Tap the heart on a tutor to save them on this device.</Text>
          </View>
        }
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  heading: { fontSize: 22, fontWeight: '800', color: colors.text, padding: spacing.lg },
  list: { paddingHorizontal: spacing.lg, gap: spacing.md, paddingBottom: 100 },
  empty: { alignItems: 'center', marginTop: spacing.xxl * 2, gap: spacing.sm },
  emptyText: { fontSize: 17, fontWeight: '700', color: colors.text },
  emptyHint: { fontSize: 14, color: colors.textMuted, textAlign: 'center', paddingHorizontal: spacing.xl },
});
