import { View, Text, StyleSheet, type ViewStyle } from 'react-native';
import { Image } from 'expo-image';
import { colors } from '../theme/colors';

/**
 * Avatar that NEVER hits the network for a fallback.
 *
 * Previously every screen fell back to `https://ui-avatars.com/api/?...`, which
 * is an external internet request per avatar. On a phone with slow internet
 * those hang, so cards looked empty long after the (tiny, fast) JSON arrived.
 * Initials are now rendered locally, and real photos are disk-cached.
 */
export function Avatar({
  uri,
  name,
  size = 48,
  style,
}: {
  uri?: string | null;
  name?: string | null;
  size?: number;
  style?: ViewStyle;
}) {
  const initials = (name ?? '')
    .trim()
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((w) => w[0]?.toUpperCase() ?? '')
    .join('');

  const box: ViewStyle = { width: size, height: size, borderRadius: size / 2 };

  if (uri) {
    return (
      <Image
        source={{ uri }}
        // expo-image types its style as ImageStyle; our box/style are plain
        // view-ish styles (size, radius, margins), which are valid at runtime.
        style={[box, styles.img, style] as never}
        contentFit="cover"
        cachePolicy="memory-disk"
        transition={150}
      />
    );
  }

  return (
    <View style={[box, styles.fallback, style]}>
      <Text style={[styles.initials, { fontSize: size * 0.38 }]}>{initials || '?'}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  img: { backgroundColor: colors.surface },
  fallback: {
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  initials: { color: colors.primaryDark, fontWeight: '800' },
});
