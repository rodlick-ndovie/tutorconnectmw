import { useState } from 'react';
import { ScrollView, View, Text, StyleSheet, Pressable, ActivityIndicator, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import { meApi, uploadsApi } from '../../src/api/endpoints';
import { pickDocument } from '../../src/lib/filePicker';
import { colors, radius, spacing } from '../../src/theme/colors';

type ResubmitDoc = { document_type: string; resubmission_message?: string };
const FALLBACK_DOCS: ResubmitDoc[] = [
  { document_type: 'national_id' },
  { document_type: 'academic_certificates' },
  { document_type: 'teaching_qualification' },
  { document_type: 'police_clearance' },
];

const pretty = (t: string) => t.replace(/_/g, ' ').replace(/\b\w/g, (m) => m.toUpperCase());

export default function Resubmit() {
  const router = useRouter();
  const qc = useQueryClient();
  const profile = useQuery({ queryKey: ['me', 'profile'], queryFn: meApi.profile });

  const [uploading, setUploading] = useState<string | null>(null);
  const [done, setDone] = useState<Record<string, boolean>>({});
  const [submitting, setSubmitting] = useState(false);

  const docs =
    (profile.data?.resubmissionDocs?.length ?? 0) > 0
      ? profile.data!.resubmissionDocs
      : FALLBACK_DOCS;

  async function uploadDoc(type: string) {
    const file = await pickDocument(); // PDF or photo
    if (!file) return;
    setUploading(type);
    try {
      await uploadsApi.document(file, type);
      setDone((d) => ({ ...d, [type]: true }));
    } catch (e) {
      Alert.alert('Upload failed', (e as Error).message);
    } finally {
      setUploading(null);
    }
  }

  const anyUploaded = Object.values(done).some(Boolean);

  const submit = async () => {
    setSubmitting(true);
    try {
      await meApi.completeResubmission();
      qc.invalidateQueries({ queryKey: ['me', 'profile'] });
      Alert.alert('Submitted', 'Your documents were sent for review. An admin will check them shortly.', [
        { text: 'Done', onPress: () => router.replace('/(tabs)/profile') },
      ]);
    } catch (e) {
      Alert.alert('Could not submit', (e as Error).message);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Resubmit Documents', headerShown: true }} />
      <ScrollView contentContainerStyle={styles.content}>
        <View style={styles.notice}>
          <Ionicons name="alert-circle" size={20} color="#B45309" />
          <Text style={styles.noticeText}>
            {profile.data?.resubmissionMessage ||
              'An administrator asked you to re-upload some documents. Please provide clear photos below.'}
          </Text>
        </View>

        <Text style={styles.section}>Documents to re-upload</Text>
        {docs.map((d) => {
          const isDone = done[d.document_type];
          return (
            <View key={d.document_type} style={styles.docCard}>
              <View style={styles.docHead}>
                <Ionicons
                  name={isDone ? 'checkmark-circle' : 'document-attach-outline'}
                  size={20}
                  color={isDone ? colors.success : colors.primary}
                />
                <Text style={styles.docLabel}>{pretty(d.document_type)}</Text>
              </View>
              {d.resubmission_message ? <Text style={styles.docMsg}>{d.resubmission_message}</Text> : null}
              <Pressable
                style={[styles.uploadBtn, isDone && styles.uploadBtnDone]}
                onPress={() => uploadDoc(d.document_type)}
                disabled={uploading === d.document_type}
              >
                {uploading === d.document_type ? (
                  <ActivityIndicator size="small" color={colors.primary} />
                ) : (
                  <>
                    <Ionicons
                      name={isDone ? 'refresh' : 'cloud-upload-outline'}
                      size={16}
                      color={isDone ? colors.success : colors.primary}
                    />
                    <Text style={[styles.uploadText, isDone && { color: colors.success }]}>
                      {isDone ? 'Re-uploaded — replace' : 'Upload'}
                    </Text>
                  </>
                )}
              </Pressable>
            </View>
          );
        })}

        <Pressable
          style={[styles.submit, (!anyUploaded || submitting) && { opacity: 0.5 }]}
          onPress={submit}
          disabled={!anyUploaded || submitting}
        >
          {submitting ? (
            <ActivityIndicator color={colors.white} />
          ) : (
            <Text style={styles.submitText}>Submit for review</Text>
          )}
        </Pressable>
        <Text style={styles.hint}>
          Submitting sends your account back to “pending” so an admin can re-check your documents.
        </Text>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.lg, paddingBottom: spacing.xxl },
  notice: {
    flexDirection: 'row',
    gap: spacing.sm,
    backgroundColor: '#FEF3C7',
    borderRadius: radius.md,
    padding: spacing.md,
  },
  noticeText: { flex: 1, fontSize: 13, color: '#92400E', lineHeight: 18 },
  section: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.textLight,
    letterSpacing: 0.6,
    textTransform: 'uppercase',
    marginTop: spacing.xl,
    marginBottom: spacing.sm,
  },
  docCard: {
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.lg,
    padding: spacing.md,
    marginBottom: spacing.md,
    gap: spacing.sm,
  },
  docHead: { flexDirection: 'row', alignItems: 'center', gap: spacing.sm },
  docLabel: { fontSize: 15, fontWeight: '700', color: colors.text },
  docMsg: { fontSize: 13, color: colors.textMuted, lineHeight: 18 },
  uploadBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 6,
    borderWidth: 1.5,
    borderStyle: 'dashed',
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingVertical: spacing.md,
  },
  uploadBtnDone: { borderStyle: 'solid', borderColor: colors.success, backgroundColor: colors.successBg },
  uploadText: { color: colors.primary, fontWeight: '700', fontSize: 14 },
  submit: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.lg,
  },
  submitText: { color: colors.white, fontWeight: '800', fontSize: 16, textAlign: 'center', paddingHorizontal: 2 },
  hint: { fontSize: 12, color: colors.textLight, textAlign: 'center', marginTop: spacing.sm, lineHeight: 17 },
});
