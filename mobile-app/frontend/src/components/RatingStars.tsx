import { View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../theme/colors';

export function RatingStars({ rating, reviewCount }: { rating: number; reviewCount?: number }) {
  const full = Math.floor(rating);
  const half = rating - full >= 0.5;
  return (
    <View style={styles.row}>
      {[0, 1, 2, 3, 4].map((i) => (
        <Ionicons
          key={i}
          name={i < full ? 'star' : i === full && half ? 'star-half' : 'star-outline'}
          size={14}
          color={colors.star}
        />
      ))}
      {reviewCount !== undefined && (
        <Text style={styles.count}>{reviewCount}+ Reviews</Text>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  row: { flexDirection: 'row', alignItems: 'center', gap: 2 },
  count: { marginLeft: 6, color: colors.textMuted, fontSize: 12, fontWeight: '600' },
});
