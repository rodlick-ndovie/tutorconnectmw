import { View, Text, StyleSheet, Pressable } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors, radius, spacing } from '../theme/colors';

/**
 * Shown when a network/API request fails. Makes connectivity problems visible
 * (with a Retry) instead of silently falling through to an empty "nothing here"
 * message — the usual reason the tutor list "doesn't show" is the API being
 * unreachable, not that there are no tutors.
 */
export function ErrorState({
  message,
  onRetry,
  compact,
}: {
  message?: string;
  onRetry?: () => void;
  compact?: boolean;
}) {
  return (
    <View style={[styles.wrap, compact && styles.compact]}>
      <Ionicons name="cloud-offline-outline" size={compact ? 28 : 40} color={colors.textLight} />
      <Text style={styles.title}>Couldn't load</Text>
      <Text style={styles.msg}>{message || 'Please check your connection and try again.'}</Text>
      {onRetry && (
        <Pressable style={styles.btn} onPress={onRetry}>
          <Ionicons name="refresh" size={16} color={colors.white} />
          <Text style={styles.btnText}>Retry</Text>
        </Pressable>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { alignItems: 'center', justifyContent: 'center', gap: spacing.sm, paddingVertical: spacing.xxl },
  compact: { paddingVertical: spacing.xl },
  title: { fontSize: 16, fontWeight: '700', color: colors.text },
  msg: { fontSize: 13, color: colors.textMuted, textAlign: 'center', paddingHorizontal: spacing.xl },
  btn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.xs,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.sm,
    marginTop: spacing.sm,
  },
  btnText: { color: colors.white, fontWeight: '700', fontSize: 14 },
});
