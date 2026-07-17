import { useState } from 'react';
import {
  Modal,
  View,
  Text,
  Pressable,
  StyleSheet,
  TextInput,
  ActivityIndicator,
  Alert,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useQueryClient } from '@tanstack/react-query';
import { tutorsApi } from '../api/endpoints';
import { colors, radius, spacing } from '../theme/colors';

// Public review form — just a name + rating, like the website. No account.
export function ReviewSheet({
  tutorId,
  visible,
  onClose,
}: {
  tutorId: number;
  visible: boolean;
  onClose: () => void;
}) {
  const qc = useQueryClient();
  const [reviewerName, setName] = useState('');
  const [rating, setRating] = useState(5);
  const [comment, setComment] = useState('');
  const [anon, setAnon] = useState(false);
  const [busy, setBusy] = useState(false);

  const submit = async () => {
    setBusy(true);
    try {
      await tutorsApi.addReview(tutorId, { reviewerName, rating, comment, isAnonymous: anon });
      qc.invalidateQueries({ queryKey: ['tutor', tutorId] });
      qc.invalidateQueries({ queryKey: ['tutor', tutorId, 'reviews'] });
      Alert.alert('Thank you!', 'Your review has been submitted.');
      setName('');
      setComment('');
      onClose();
    } catch (e) {
      Alert.alert('Could not submit', (e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  return (
    <Modal visible={visible} transparent animationType="slide" onRequestClose={onClose}>
      <KeyboardAvoidingView
        style={{ flex: 1 }}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      >
      <Pressable style={styles.backdrop} onPress={onClose}>
        <Pressable style={styles.sheet} onPress={(e) => e.stopPropagation()}>
          <View style={styles.handle} />
          <Text style={styles.title}>Write a review</Text>

          <View style={styles.stars}>
            {[1, 2, 3, 4, 5].map((i) => (
              <Pressable key={i} onPress={() => setRating(i)}>
                <Ionicons
                  name={i <= rating ? 'star' : 'star-outline'}
                  size={34}
                  color={colors.star}
                />
              </Pressable>
            ))}
          </View>

          <TextInput
            style={styles.input}
            placeholder="Your name"
            placeholderTextColor={colors.textLight}
            value={reviewerName}
            onChangeText={setName}
          />
          <TextInput
            style={[styles.input, { height: 100, textAlignVertical: 'top' }]}
            placeholder="Your review (optional)"
            placeholderTextColor={colors.textLight}
            value={comment}
            onChangeText={setComment}
            multiline
          />

          <Pressable style={styles.anonRow} onPress={() => setAnon((a) => !a)}>
            <Ionicons
              name={anon ? 'checkbox' : 'square-outline'}
              size={22}
              color={anon ? colors.primary : colors.textLight}
            />
            <Text style={styles.anonText}>Post anonymously</Text>
          </Pressable>

          <Pressable
            style={[styles.submit, (!reviewerName || busy) && { opacity: 0.6 }]}
            onPress={submit}
            disabled={!reviewerName || busy}
          >
            {busy ? <ActivityIndicator color={colors.white} /> : <Text style={styles.submitText}>Submit Review</Text>}
          </Pressable>
        </Pressable>
      </Pressable>
      </KeyboardAvoidingView>
    </Modal>
  );
}

const styles = StyleSheet.create({
  backdrop: { flex: 1, backgroundColor: 'rgba(0,0,0,0.4)', justifyContent: 'flex-end' },
  sheet: {
    backgroundColor: colors.white,
    borderTopLeftRadius: radius.xl,
    borderTopRightRadius: radius.xl,
    padding: spacing.lg,
    paddingBottom: spacing.xxl,
  },
  handle: { width: 40, height: 4, borderRadius: 2, backgroundColor: colors.border, alignSelf: 'center', marginBottom: spacing.md },
  title: { fontSize: 18, fontWeight: '800', color: colors.text, marginBottom: spacing.md },
  stars: { flexDirection: 'row', justifyContent: 'center', gap: spacing.sm, marginBottom: spacing.lg },
  input: {
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    fontSize: 15,
    color: colors.text,
    backgroundColor: colors.surface,
    marginBottom: spacing.sm,
  },
  anonRow: { flexDirection: 'row', alignItems: 'center', gap: spacing.sm, marginVertical: spacing.sm },
  anonText: { color: colors.textMuted, fontSize: 14 },
  submit: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.sm,
  },
  submitText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
