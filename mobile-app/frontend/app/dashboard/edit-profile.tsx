import { useEffect, useState } from 'react';
import { ScrollView, View, Text, StyleSheet, Pressable, ActivityIndicator, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { meApi, uploadsApi, ProfileUpdate } from '../../src/api/endpoints';
import { pickImage, pickDocument } from '../../src/lib/filePicker';
import { Avatar } from '../../src/components/Avatar';
import { LabeledField, ToggleRow } from '../../src/components/Field';
import { colors, radius, spacing } from '../../src/theme/colors';

const DOC_TYPES = [
  { type: 'national_id', label: 'National ID' },
  { type: 'academic_certificates', label: 'Academic Certificates' },
  { type: 'teaching_qualification', label: 'Teaching Qualification' },
  { type: 'police_clearance', label: 'Police Clearance' },
];

export default function EditProfile() {
  const router = useRouter();
  const qc = useQueryClient();
  const profile = useQuery({ queryKey: ['me', 'profile'], queryFn: meApi.profile });
  const [form, setForm] = useState<ProfileUpdate>({});

  // Seed the editable form once the profile loads.
  useEffect(() => {
    if (profile.data) {
      const p = profile.data;
      setForm({
        firstName: p.firstName ?? '',
        lastName: p.lastName ?? '',
        phone: p.phone ?? '',
        district: p.district ?? '',
        location: p.location ?? '',
        experienceYears: p.experienceYears ?? 0,
        teachingMode: p.teachingMode ?? '',
        bio: p.bio ?? '',
        whatsappNumber: p.whatsappNumber ?? '',
        schoolName: p.schoolName ?? '',
        bestCallTime: p.bestCallTime ?? '',
        phoneVisible: p.phoneVisible,
        emailVisible: p.emailVisible,
        isEmployed: p.isEmployed,
      });
    }
  }, [profile.data]);

  const save = useMutation({
    mutationFn: () => meApi.updateProfile(form),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['me', 'profile'] });
      Alert.alert('Saved', 'Your profile has been updated.');
      router.back();
    },
    onError: (e) => Alert.alert('Error', (e as Error).message),
  });

  const set = <K extends keyof ProfileUpdate>(k: K, v: ProfileUpdate[K]) =>
    setForm((f) => ({ ...f, [k]: v }));

  const [uploadingKey, setUploadingKey] = useState<string | null>(null);
  // Things uploaded during THIS session — shown as done immediately, before refetch.
  const [justUploaded, setJustUploaded] = useState<Record<string, boolean>>({});

  // "Uploaded" = already on the server OR uploaded just now.
  const photoDone = !!profile.data?.profilePicture || justUploaded.profile;
  const coverDone = !!profile.data?.coverPhoto || justUploaded.cover;
  const serverDocs = profile.data?.uploadedDocuments ?? [];
  const isDocDone = (type: string) => serverDocs.includes(type) || justUploaded[type];

  async function uploadImage(kind: 'profile' | 'cover') {
    const file = await pickImage();
    if (!file) return;
    setUploadingKey(kind);
    try {
      if (kind === 'profile') await uploadsApi.profilePhoto(file);
      else await uploadsApi.coverPhoto(file);
      setJustUploaded((s) => ({ ...s, [kind]: true }));
      qc.invalidateQueries({ queryKey: ['me', 'profile'] });
    } catch (e) {
      Alert.alert('Upload failed', (e as Error).message);
    } finally {
      setUploadingKey(null);
    }
  }

  async function uploadDocument(type: string) {
    // Lets the tutor pick a PDF from Files, not just a photo.
    const file = await pickDocument();
    if (!file) return;
    setUploadingKey(type);
    try {
      await uploadsApi.document(file, type);
      setJustUploaded((s) => ({ ...s, [type]: true }));
      qc.invalidateQueries({ queryKey: ['me', 'profile'] });
    } catch (e) {
      Alert.alert('Upload failed', (e as Error).message);
    } finally {
      setUploadingKey(null);
    }
  }

  if (profile.isLoading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Edit Profile', headerShown: true }} />
      <ScrollView contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <View style={styles.photoRow}>
          <Avatar
            uri={profile.data?.profilePicture}
            name={`${profile.data?.firstName ?? ''} ${profile.data?.lastName ?? ''}`.trim() || 'U'}
            size={76}
          />
          <View style={{ flex: 1, gap: spacing.sm }}>
            <Pressable
              style={[styles.photoBtn, photoDone && styles.photoBtnDone]}
              onPress={() => uploadImage('profile')}
              disabled={uploadingKey === 'profile'}
            >
              {uploadingKey === 'profile' ? (
                <ActivityIndicator color={colors.primary} />
              ) : photoDone ? (
                <>
                  <Ionicons name="checkmark-circle" size={16} color={colors.success} />
                  <Text style={[styles.photoBtnText, { color: colors.success }]}>Photo uploaded — change</Text>
                </>
              ) : (
                <>
                  <Ionicons name="camera-outline" size={16} color={colors.primary} />
                  <Text style={styles.photoBtnText}>Change photo</Text>
                </>
              )}
            </Pressable>
            <Pressable
              style={[styles.photoBtn, coverDone && styles.photoBtnDone]}
              onPress={() => uploadImage('cover')}
              disabled={uploadingKey === 'cover'}
            >
              {uploadingKey === 'cover' ? (
                <ActivityIndicator color={colors.primary} />
              ) : coverDone ? (
                <>
                  <Ionicons name="checkmark-circle" size={16} color={colors.success} />
                  <Text style={[styles.photoBtnText, { color: colors.success }]}>Cover uploaded — change</Text>
                </>
              ) : (
                <>
                  <Ionicons name="image-outline" size={16} color={colors.primary} />
                  <Text style={styles.photoBtnText}>Change cover</Text>
                </>
              )}
            </Pressable>
          </View>
        </View>

        <LabeledField label="First name" value={form.firstName} onChangeText={(v) => set('firstName', v)} />
        <LabeledField label="Last name" value={form.lastName} onChangeText={(v) => set('lastName', v)} />
        <LabeledField label="Phone" value={form.phone} onChangeText={(v) => set('phone', v)} keyboardType="phone-pad" />
        <LabeledField label="WhatsApp number" value={form.whatsappNumber} onChangeText={(v) => set('whatsappNumber', v)} keyboardType="phone-pad" />
        <LabeledField label="District" value={form.district} onChangeText={(v) => set('district', v)} />
        <LabeledField label="Location / Area" value={form.location} onChangeText={(v) => set('location', v)} />
        <LabeledField
          label="Years of experience"
          value={String(form.experienceYears ?? '')}
          onChangeText={(v) => set('experienceYears', Number(v.replace(/[^\d]/g, '')) || 0)}
          keyboardType="number-pad"
        />
        <LabeledField label="Teaching mode" value={form.teachingMode} onChangeText={(v) => set('teachingMode', v)} />
        <LabeledField label="School name (if employed)" value={form.schoolName} onChangeText={(v) => set('schoolName', v)} />
        <LabeledField label="Best time to call" value={form.bestCallTime} onChangeText={(v) => set('bestCallTime', v)} />
        <LabeledField label="Bio" value={form.bio} onChangeText={(v) => set('bio', v)} multiline />

        <Text style={styles.section}>Privacy</Text>
        <ToggleRow label="Show my phone number" value={!!form.phoneVisible} onToggle={() => set('phoneVisible', !form.phoneVisible)} />
        <ToggleRow label="Show my email" value={!!form.emailVisible} onToggle={() => set('emailVisible', !form.emailVisible)} />
        <ToggleRow label="I am currently employed" value={!!form.isEmployed} onToggle={() => set('isEmployed', !form.isEmployed)} />

        <Text style={styles.section}>Verification Documents</Text>
        <Text style={styles.hint}>Upload a clear photo of each document. An admin will review them.</Text>
        {DOC_TYPES.map((d) => {
          const done = isDocDone(d.type);
          return (
            <Pressable
              key={d.type}
              style={[styles.docRow, done && styles.docRowDone]}
              onPress={() => uploadDocument(d.type)}
              disabled={uploadingKey === d.type}
            >
              <Ionicons
                name={done ? 'checkmark-circle' : 'document-attach-outline'}
                size={20}
                color={done ? colors.success : colors.primary}
              />
              <View style={{ flex: 1 }}>
                <Text style={styles.docLabel}>{d.label}</Text>
                {done && <Text style={styles.docDoneText}>Uploaded · tap to replace</Text>}
              </View>
              {uploadingKey === d.type ? (
                <ActivityIndicator color={colors.primary} />
              ) : (
                <Ionicons
                  name={done ? 'refresh' : 'cloud-upload-outline'}
                  size={20}
                  color={done ? colors.success : colors.accent}
                />
              )}
            </Pressable>
          );
        })}

        <Pressable style={[styles.save, save.isPending && { opacity: 0.7 }]} onPress={() => save.mutate()} disabled={save.isPending}>
          {save.isPending ? <ActivityIndicator color={colors.white} /> : <Text style={styles.saveText}>Save Changes</Text>}
        </Pressable>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg },
  content: { padding: spacing.lg, paddingBottom: spacing.xxl },
  section: { fontSize: 16, fontWeight: '700', color: colors.text, marginTop: spacing.lg, marginBottom: spacing.sm },
  hint: { fontSize: 13, color: colors.textMuted, marginBottom: spacing.sm },
  photoRow: { flexDirection: 'row', alignItems: 'center', gap: spacing.lg, marginBottom: spacing.lg },
  avatar: { width: 76, height: 76, borderRadius: 38, backgroundColor: colors.surface },
  photoBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.xs,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.pill,
    paddingVertical: spacing.sm,
  },
  photoBtnText: { color: colors.primary, fontWeight: '700', fontSize: 14 },
  photoBtnDone: { borderColor: colors.success, backgroundColor: colors.successBg },
  docRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    padding: spacing.md,
    marginBottom: spacing.sm,
  },
  docRowDone: { borderColor: colors.success, backgroundColor: colors.successBg },
  docLabel: { fontSize: 14, fontWeight: '600', color: colors.text },
  docDoneText: { fontSize: 12, color: colors.success, fontWeight: '600', marginTop: 1 },
  save: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    alignItems: 'center',
    marginTop: spacing.xl,
  },
  saveText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
