import { View, Text, ActivityIndicator, StyleSheet } from 'react-native';
import { colors, spacing } from '../theme/colors';

/** Footer for infinite lists: a spinner while loading more, or an end marker. */
export function ListFooter({
  loadingMore,
  hasMore,
  count,
  endLabel = 'That’s everything',
}: {
  loadingMore: boolean;
  hasMore: boolean;
  count: number;
  endLabel?: string;
}) {
  if (loadingMore) {
    return (
      <View style={styles.wrap}>
        <ActivityIndicator color={colors.primary} />
      </View>
    );
  }
  // Only show the end marker once there's a meaningful amount loaded.
  if (!hasMore && count > 6) {
    return (
      <View style={styles.wrap}>
        <Text style={styles.end}>{endLabel}</Text>
      </View>
    );
  }
  return null;
}

const styles = StyleSheet.create({
  wrap: { paddingVertical: spacing.lg, alignItems: 'center' },
  end: { color: colors.textLight, fontSize: 12 },
});
