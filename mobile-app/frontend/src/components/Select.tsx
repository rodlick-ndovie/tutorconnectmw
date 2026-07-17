import { useMemo, useState } from 'react';
import { Modal, View, Text, TextInput, Pressable, FlatList, StyleSheet } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { colors, radius, spacing } from '../theme/colors';

/**
 * Dropdown-style picker: a field that opens a searchable modal list.
 * Used instead of a long pill row when options are numerous (e.g. 28 districts).
 */
export function Select({
  label,
  value,
  options,
  placeholder = 'Select…',
  searchable = true,
  onChange,
  allowClear = true,
}: {
  label?: string;
  value?: string;
  options: string[];
  placeholder?: string;
  searchable?: boolean;
  onChange: (value: string | undefined) => void;
  allowClear?: boolean;
}) {
  const [open, setOpen] = useState(false);
  const [q, setQ] = useState('');

  const filtered = useMemo(() => {
    const needle = q.trim().toLowerCase();
    if (!needle) return options;
    return options.filter((o) => o.toLowerCase().includes(needle));
  }, [options, q]);

  const close = () => {
    setOpen(false);
    setQ('');
  };

  return (
    <View style={{ marginBottom: spacing.md }}>
      {label && <Text style={styles.label}>{label}</Text>}

      <Pressable style={styles.field} onPress={() => setOpen(true)}>
        <Text style={[styles.fieldText, !value && styles.placeholder]} numberOfLines={1}>
          {value || placeholder}
        </Text>
        <Ionicons name="chevron-down" size={18} color={colors.textMuted} />
      </Pressable>

      <Modal visible={open} animationType="slide" transparent onRequestClose={close}>
        <Pressable style={styles.backdrop} onPress={close} />
        <SafeAreaView style={styles.sheet} edges={['bottom']}>
          <View style={styles.handle} />
          <View style={styles.header}>
            <Text style={styles.title}>{label ?? 'Select'}</Text>
            <Pressable onPress={close} hitSlop={10}>
              <Ionicons name="close" size={22} color={colors.textMuted} />
            </Pressable>
          </View>

          {searchable && (
            <View style={styles.search}>
              <Ionicons name="search" size={18} color={colors.textLight} />
              <TextInput
                style={styles.searchInput}
                placeholder="Search…"
                placeholderTextColor={colors.textLight}
                value={q}
                onChangeText={setQ}
                autoCorrect={false}
              />
              {q.length > 0 && (
                <Pressable onPress={() => setQ('')} hitSlop={8}>
                  <Ionicons name="close-circle" size={18} color={colors.textLight} />
                </Pressable>
              )}
            </View>
          )}

          <FlatList
            data={filtered}
            keyExtractor={(o) => o}
            keyboardShouldPersistTaps="handled"
            style={{ maxHeight: 380 }}
            renderItem={({ item }) => {
              const selected = item === value;
              return (
                <Pressable
                  style={styles.option}
                  onPress={() => {
                    onChange(selected && allowClear ? undefined : item);
                    close();
                  }}
                >
                  <Text style={[styles.optionText, selected && styles.optionTextSelected]}>{item}</Text>
                  {selected && <Ionicons name="checkmark" size={18} color={colors.primary} />}
                </Pressable>
              );
            }}
            ListEmptyComponent={<Text style={styles.empty}>No matches.</Text>}
          />

          {allowClear && value && (
            <Pressable
              style={styles.clear}
              onPress={() => {
                onChange(undefined);
                close();
              }}
            >
              <Text style={styles.clearText}>Clear selection</Text>
            </Pressable>
          )}
        </SafeAreaView>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  label: { fontSize: 13, fontWeight: '600', color: colors.text, marginBottom: 6 },
  field: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    backgroundColor: colors.surface,
  },
  fieldText: { flex: 1, fontSize: 15, color: colors.text },
  placeholder: { color: colors.textLight },

  backdrop: { flex: 1, backgroundColor: 'rgba(0,0,0,0.45)' },
  sheet: {
    backgroundColor: colors.white,
    borderTopLeftRadius: radius.xl,
    borderTopRightRadius: radius.xl,
    paddingBottom: spacing.sm,
  },
  handle: {
    width: 40,
    height: 4,
    borderRadius: 2,
    backgroundColor: colors.border,
    alignSelf: 'center',
    marginTop: spacing.sm,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
  },
  title: { fontSize: 17, fontWeight: '800', color: colors.text },
  search: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
    marginHorizontal: spacing.lg,
    marginBottom: spacing.sm,
    paddingHorizontal: spacing.md,
    paddingVertical: spacing.sm,
    borderRadius: radius.md,
    borderWidth: 1,
    borderColor: colors.border,
    backgroundColor: colors.surface,
  },
  searchInput: { flex: 1, fontSize: 15, color: colors.text, paddingVertical: 4 },
  option: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  optionText: { fontSize: 15, color: colors.text },
  optionTextSelected: { color: colors.primary, fontWeight: '700' },
  empty: { textAlign: 'center', color: colors.textMuted, padding: spacing.xl },
  clear: { alignItems: 'center', paddingVertical: spacing.md },
  clearText: { color: colors.danger, fontWeight: '700', fontSize: 14 },
});
