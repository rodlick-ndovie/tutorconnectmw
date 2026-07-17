import { View, Text, TextInput, Pressable, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors, radius, spacing } from '../theme/colors';

export function LabeledField({
  label,
  value,
  onChangeText,
  ...rest
}: { label: string } & React.ComponentProps<typeof TextInput>) {
  return (
    <View style={styles.wrap}>
      <Text style={styles.label}>{label}</Text>
      <TextInput
        {...rest}
        value={value}
        onChangeText={onChangeText}
        placeholderTextColor={colors.textLight}
        style={[styles.input, rest.multiline && { height: 110, textAlignVertical: 'top' }]}
      />
    </View>
  );
}

export function ToggleRow({
  label,
  value,
  onToggle,
}: {
  label: string;
  value: boolean;
  onToggle: () => void;
}) {
  return (
    <Pressable style={styles.toggle} onPress={onToggle}>
      <Text style={styles.toggleLabel}>{label}</Text>
      <Ionicons
        name={value ? 'toggle' : 'toggle-outline'}
        size={34}
        color={value ? colors.primary : colors.textLight}
      />
    </Pressable>
  );
}

const styles = StyleSheet.create({
  wrap: { marginBottom: spacing.md },
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginBottom: 4 },
  input: {
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    fontSize: 15,
    color: colors.text,
    backgroundColor: colors.surface,
  },
  toggle: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: spacing.sm,
  },
  toggleLabel: { fontSize: 15, color: colors.text },
});
