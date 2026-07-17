import { useState } from 'react';
import {
  Modal,
  View,
  Text,
  Pressable,
  StyleSheet,
  Linking,
  TextInput,
  ScrollView,
  ActivityIndicator,
  Alert,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { tutorsApi } from '../api/endpoints';
import { toIntlMw } from '../utils/phone';
import { colors, radius, spacing } from '../theme/colors';
import type { TutorProfile } from '../types';

// Contact a tutor the way the website does: WhatsApp / call / email direct,
// plus a message form that emails the tutor. No account required.
export function ContactSheet({
  tutor,
  visible,
  onClose,
}: {
  tutor: TutorProfile;
  visible: boolean;
  onClose: () => void;
}) {
  const [showForm, setShowForm] = useState(false);
  const wa = toIntlMw(tutor.whatsappNumber);
  const tel = toIntlMw(tutor.phone);

  return (
    <Modal visible={visible} transparent animationType="slide" onRequestClose={onClose}>
      <KeyboardAvoidingView
        style={{ flex: 1 }}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      >
      <Pressable style={styles.backdrop} onPress={onClose}>
        <Pressable style={styles.sheet} onPress={(e) => e.stopPropagation()}>
          <View style={styles.handle} />
          {!showForm ? (
            <>
              <Text style={styles.title}>Contact {tutor.name}</Text>
              {wa && (
                <Option
                  icon="logo-whatsapp"
                  color={colors.success}
                  label="WhatsApp"
                  onPress={() => {
                    tutorsApi.trackContact(tutor.id, 'whatsapp');
                    Linking.openURL(`https://wa.me/${wa}`);
                  }}
                />
              )}
              {tel && (
                <Option
                  icon="call"
                  color={colors.accent}
                  label="Call"
                  onPress={() => {
                    tutorsApi.trackContact(tutor.id, 'call');
                    Linking.openURL(`tel:+${tel}`);
                  }}
                />
              )}
              {tutor.email && (
                <Option
                  icon="mail"
                  color={colors.primary}
                  label="Email"
                  onPress={() => {
                    tutorsApi.trackContact(tutor.id, 'email');
                    Linking.openURL(`mailto:${tutor.email}`);
                  }}
                />
              )}
              <Option
                icon="chatbubble-ellipses"
                color={colors.primaryDark}
                label="Send a message"
                onPress={() => setShowForm(true)}
              />
            </>
          ) : (
            <ContactForm tutorId={tutor.id} onDone={onClose} onBack={() => setShowForm(false)} />
          )}
        </Pressable>
      </Pressable>
      </KeyboardAvoidingView>
    </Modal>
  );
}

function Option({
  icon,
  label,
  color,
  onPress,
}: {
  icon: keyof typeof Ionicons.glyphMap;
  label: string;
  color: string;
  onPress: () => void;
}) {
  return (
    <Pressable style={styles.option} onPress={onPress}>
      <Ionicons name={icon} size={22} color={color} />
      <Text style={styles.optionText}>{label}</Text>
      <Ionicons name="chevron-forward" size={18} color={colors.textLight} />
    </Pressable>
  );
}

function ContactForm({
  tutorId,
  onDone,
  onBack,
}: {
  tutorId: number;
  onDone: () => void;
  onBack: () => void;
}) {
  const [form, setForm] = useState({ senderName: '', senderEmail: '', senderPhone: '', subject: '', message: '' });
  const [busy, setBusy] = useState(false);
  const set = (k: keyof typeof form) => (v: string) => setForm((f) => ({ ...f, [k]: v }));

  const submit = async () => {
    setBusy(true);
    try {
      // The enquiry is saved to the tutor's in-app inbox regardless of email, so
      // submitting always succeeds. `sent` only tells us if the email heads-up
      // also went out — we reflect that in the confirmation wording.
      const res = await tutorsApi.contact(tutorId, form);
      Alert.alert(
        'Message sent',
        res.sent
          ? 'The tutor has been notified and will get back to you.'
          : 'Saved to the tutor — they will see it in their app and reach out to you.'
      );
      onDone();
    } catch (e) {
      Alert.alert('Could not send', (e as Error).message);
    } finally {
      setBusy(false);
    }
  };

  // Phone is optional but strongly encouraged — it's how the tutor calls back.
  const valid = form.senderName && form.senderEmail && form.subject && form.message;

  return (
    <ScrollView keyboardShouldPersistTaps="handled">
      <Pressable style={styles.back} onPress={onBack}>
        <Ionicons name="chevron-back" size={20} color={colors.primary} />
        <Text style={styles.backText}>Back</Text>
      </Pressable>
      <Text style={styles.title}>Send a message</Text>
      <Field placeholder="Your name" value={form.senderName} onChangeText={set('senderName')} />
      <Field
        placeholder="Your email"
        value={form.senderEmail}
        onChangeText={set('senderEmail')}
        keyboardType="email-address"
        autoCapitalize="none"
      />
      <Field
        placeholder="Your phone (so the tutor can call you back)"
        value={form.senderPhone}
        onChangeText={set('senderPhone')}
        keyboardType="phone-pad"
      />
      <Field placeholder="Subject" value={form.subject} onChangeText={set('subject')} />
      <Field placeholder="Message" value={form.message} onChangeText={set('message')} multiline />
      <Pressable
        style={[styles.submit, (!valid || busy) && { opacity: 0.6 }]}
        onPress={submit}
        disabled={!valid || busy}
      >
        {busy ? <ActivityIndicator color={colors.white} /> : <Text style={styles.submitText}>Send</Text>}
      </Pressable>
    </ScrollView>
  );
}

function Field(props: React.ComponentProps<typeof TextInput>) {
  return (
    <TextInput
      {...props}
      placeholderTextColor={colors.textLight}
      style={[styles.input, props.multiline && { height: 100, textAlignVertical: 'top' }]}
    />
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
    maxHeight: '85%',
  },
  handle: { width: 40, height: 4, borderRadius: 2, backgroundColor: colors.border, alignSelf: 'center', marginBottom: spacing.md },
  title: { fontSize: 18, fontWeight: '800', color: colors.text, marginBottom: spacing.md },
  option: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    paddingVertical: spacing.lg,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  optionText: { flex: 1, fontSize: 16, color: colors.text, fontWeight: '600' },
  back: { flexDirection: 'row', alignItems: 'center', marginBottom: spacing.sm },
  backText: { color: colors.primary, fontWeight: '600' },
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
  submit: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.sm,
  },
  submitText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
