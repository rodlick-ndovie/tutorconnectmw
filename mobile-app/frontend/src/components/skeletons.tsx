import { useEffect, useRef } from 'react';
import { Animated, View, StyleSheet, type ViewStyle, type DimensionValue } from 'react-native';
import { colors, radius, spacing } from '../theme/colors';

/**
 * Base shimmer block. Everything else is composed from this. A single looping
 * opacity animation (native-driven) drives all blocks, so it's cheap.
 */
export function Skeleton({
  width = '100%',
  height = 14,
  radius: r = 6,
  style,
}: {
  width?: DimensionValue;
  height?: number;
  radius?: number;
  style?: ViewStyle;
}) {
  const pulse = useRef(new Animated.Value(0.4)).current;

  useEffect(() => {
    const loop = Animated.loop(
      Animated.sequence([
        Animated.timing(pulse, { toValue: 1, duration: 700, useNativeDriver: true }),
        Animated.timing(pulse, { toValue: 0.4, duration: 700, useNativeDriver: true }),
      ])
    );
    loop.start();
    return () => loop.stop();
  }, [pulse]);

  return (
    <Animated.View
      style={[
        { width, height, borderRadius: r, backgroundColor: colors.border, opacity: pulse },
        style,
      ]}
    />
  );
}

/** Placeholder that mirrors a TutorCard (used on Home, Search, favorites). */
export function TutorCardSkeleton() {
  return (
    <View style={styles.card}>
      <View style={styles.row}>
        <Skeleton width={56} height={56} radius={28} />
        <View style={{ flex: 1, gap: 8 }}>
          <Skeleton width="60%" height={16} />
          <Skeleton width="40%" height={12} />
        </View>
      </View>
      <View style={styles.badges}>
        <Skeleton width={70} height={20} radius={999} />
        <Skeleton width={90} height={20} radius={999} />
      </View>
      <Skeleton width="90%" height={12} />
      <Skeleton width="75%" height={12} />
      <Skeleton width="100%" height={38} radius={999} style={{ marginTop: spacing.sm }} />
    </View>
  );
}

/** A compact row placeholder (notices, notifications, parent requests, resources). */
export function ListRowSkeleton() {
  return (
    <View style={styles.rowCard}>
      <Skeleton width={40} height={40} radius={20} />
      <View style={{ flex: 1, gap: 8 }}>
        <Skeleton width="65%" height={14} />
        <Skeleton width="45%" height={11} />
      </View>
    </View>
  );
}

/** Placeholder for a tutor / university profile detail screen. */
export function ProfileSkeleton() {
  return (
    <View style={{ flex: 1, backgroundColor: colors.bg }}>
      <Skeleton width="100%" height={140} radius={0} />
      <View style={{ paddingHorizontal: spacing.lg }}>
        <View style={{ flexDirection: 'row', gap: spacing.md, marginTop: -34 }}>
          <Skeleton width={84} height={84} radius={42} />
          <View style={{ flex: 1, gap: 8, paddingTop: spacing.xl }}>
            <Skeleton width="55%" height={20} />
            <Skeleton width="35%" height={13} />
          </View>
        </View>
        <View style={{ flexDirection: 'row', gap: spacing.md, marginTop: spacing.xl }}>
          <Skeleton width="30%" height={54} radius={12} />
          <Skeleton width="30%" height={54} radius={12} />
          <Skeleton width="30%" height={54} radius={12} />
        </View>
        <View style={{ gap: 10, marginTop: spacing.xl }}>
          <Skeleton width="40%" height={14} />
          <Skeleton width="100%" height={12} />
          <Skeleton width="95%" height={12} />
          <Skeleton width="80%" height={12} />
        </View>
        <View style={{ gap: 8, marginTop: spacing.xl }}>
          <Skeleton width="40%" height={14} />
          <View style={{ flexDirection: 'row', gap: spacing.xs, flexWrap: 'wrap' }}>
            <Skeleton width={90} height={26} radius={999} />
            <Skeleton width={110} height={26} radius={999} />
            <Skeleton width={80} height={26} radius={999} />
          </View>
        </View>
      </View>
    </View>
  );
}

/** Placeholder for a paragraph / article (notice detail). */
export function ArticleSkeleton() {
  return (
    <View style={{ padding: spacing.lg, gap: 10 }}>
      <Skeleton width="50%" height={16} />
      <Skeleton width="80%" height={22} style={{ marginTop: spacing.sm }} />
      <Skeleton width="100%" height={180} radius={12} style={{ marginVertical: spacing.md }} />
      <Skeleton width="100%" height={12} />
      <Skeleton width="96%" height={12} />
      <Skeleton width="90%" height={12} />
      <Skeleton width="70%" height={12} />
    </View>
  );
}

/** Placeholder for a stat grid (dashboard analytics). */
export function StatGridSkeleton({ count = 4 }: { count?: number }) {
  return (
    <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.md, padding: spacing.lg }}>
      {Array.from({ length: count }).map((_, i) => (
        <Skeleton key={i} width="47%" height={96} radius={16} />
      ))}
    </View>
  );
}

/** Renders `count` copies of a skeleton. */
export function SkeletonList({
  count = 5,
  Item = TutorCardSkeleton,
  gap = spacing.md,
}: {
  count?: number;
  Item?: React.ComponentType;
  gap?: number;
}) {
  return (
    <View style={{ gap }}>
      {Array.from({ length: count }).map((_, i) => (
        <Item key={i} />
      ))}
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.lg,
    gap: spacing.sm,
  },
  row: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  badges: { flexDirection: 'row', gap: spacing.xs, marginTop: spacing.xs },
  rowCard: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
  },
});
