import { View, Text, Pressable, StyleSheet } from 'react-native';
import { colors, spacing } from '../theme/colors';

export function SectionHeader({ title, onSeeAll }: { title: string; onSeeAll?: () => void }) {
  return (
    <View style={styles.row}>
      <Text style={styles.title}>{title}</Text>
      {onSeeAll && (
        <Pressable onPress={onSeeAll}>
          <Text style={styles.seeAll}>See all</Text>
        </Pressable>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: spacing.md,
    marginTop: spacing.lg,
  },
  title: { fontSize: 18, fontWeight: '700', color: colors.text },
  seeAll: { color: colors.accent, fontWeight: '600', fontSize: 14 },
});
