import {
  ScrollView,
  View,
  Text,
  StyleSheet,
  Pressable,
  ActivityIndicator,
  Linking,
  RefreshControl,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Image } from 'expo-image';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useLocalSearchParams } from 'expo-router';
import { useQuery } from '@tanstack/react-query';
import { universityApi } from '../../src/api/endpoints';
import { ErrorState } from '../../src/components/ErrorState';
import { ProfileSkeleton } from '../../src/components/skeletons';
import { useRefresh } from '../../src/hooks/useRefresh';
import { toIntlMw } from '../../src/utils/phone';
import { colors, gradients, radius, spacing } from '../../src/theme/colors';

const mwk = (n: number | null) => (n === null ? null : `MWK ${n.toLocaleString()}`);

export default function UniversityProfile() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const tutorId = Number(id);
  const q = useQuery({
    queryKey: ['university', 'tutor', tutorId],
    queryFn: () => universityApi.tutor(tutorId),
  });
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  if (q.isLoading) return <ProfileSkeleton />;
  if (q.isError || !q.data) {
    return (
      <View style={styles.center}>
        <ErrorState message={(q.error as Error)?.message} onRetry={() => q.refetch()} />
      </View>
    );
  }

  const t = q.data;
  const isFirm = t.accountType === 'firm';
  const tel = toIntlMw(t.phone);
  const rates = [
    { label: 'Hourly', value: mwk(t.rates.hourly) },
    { label: 'Consultation', value: mwk(t.rates.consultation) },
    { label: 'Dissertation', value: mwk(t.rates.dissertation) },
    { label: 'Exam prep', value: mwk(t.rates.examPreparation) },
  ].filter((r) => r.value);

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: isFirm ? 'Company' : 'University Tutor', headerShown: true }} />
      <ScrollView
        contentContainerStyle={styles.scroll}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
      >
        <LinearGradient colors={[...gradients.hero]} style={styles.cover} />

        <View style={styles.body}>
          <View style={styles.header}>
            {t.profilePicture ? (
              <Image source={{ uri: t.profilePicture }} style={styles.avatar} contentFit="cover" />
            ) : (
              <View style={[styles.avatar, styles.avatarFallback]}>
                <Ionicons name={isFirm ? 'business' : 'person'} size={30} color={colors.primary} />
              </View>
            )}
            <View style={{ flex: 1, paddingTop: spacing.sm }}>
              <Text style={styles.name}>{t.name}</Text>
              <Text style={styles.meta}>
                {[t.cityLocation, t.teachingMode].filter(Boolean).join(' · ')}
              </Text>
              <View style={styles.pills}>
                <View style={[styles.pill, isFirm && styles.pillFirm]}>
                  <Text style={[styles.pillText, isFirm && styles.pillTextFirm]}>
                    {isFirm ? 'Company' : 'University Tutor'}
                  </Text>
                </View>
                <View style={styles.pill}>
                  <Text style={styles.pillText}>{t.subscriptionPlan}</Text>
                </View>
              </View>
            </View>
          </View>

          <Text style={styles.ref}>Ref: {t.referenceCode}</Text>

          <Section title="About">
            <Text style={styles.bio}>{t.bio}</Text>
          </Section>

          {t.serviceAreas.length > 0 && (
            <Section title="Service areas">
              <Chips items={t.serviceAreas} />
            </Section>
          )}
          {t.specializations.length > 0 && (
            <Section title="Specializations">
              <Chips items={t.specializations} />
            </Section>
          )}
          {t.institutions.length > 0 && (
            <Section title={isFirm ? 'Institutions served' : 'Institutions'}>
              <Chips items={t.institutions} />
            </Section>
          )}

          {rates.length > 0 && (
            <Section title="Rates">
              {rates.map((r) => (
                <View key={r.label} style={styles.rateRow}>
                  <Text style={styles.rateLabel}>{r.label}</Text>
                  <Text style={styles.rateValue}>{r.value}</Text>
                </View>
              ))}
            </Section>
          )}

          {(t.availableDays.length > 0 || t.preferredTimes.length > 0) && (
            <Section title="Availability">
              {t.availableDays.length > 0 && <Chips items={t.availableDays} />}
              {t.preferredTimes.length > 0 && (
                <Text style={styles.bio}>{t.preferredTimes.join(', ')}</Text>
              )}
            </Section>
          )}
        </View>
      </ScrollView>

      <View style={styles.footer}>
        {tel && (
          <Pressable style={styles.callBtn} onPress={() => Linking.openURL(`tel:${tel}`)}>
            <Ionicons name="call" size={18} color={colors.primary} />
            <Text style={styles.callText}>Call</Text>
          </Pressable>
        )}
        <Pressable
          style={styles.mailBtn}
          onPress={() => Linking.openURL(`mailto:${t.email}?subject=TutorConnect enquiry`)}
        >
          <Ionicons name="mail" size={18} color={colors.white} />
          <Text style={styles.mailText}>Email {isFirm ? 'company' : 'tutor'}</Text>
        </Pressable>
      </View>
    </SafeAreaView>
  );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <View style={styles.section}>
      <Text style={styles.sectionTitle}>{title}</Text>
      {children}
    </View>
  );
}

function Chips({ items }: { items: string[] }) {
  return (
    <View style={styles.chips}>
      {items.map((s) => (
        <View key={s} style={styles.chip}>
          <Text style={styles.chipText}>{s}</Text>
        </View>
      ))}
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg },
  scroll: { paddingBottom: 100 },
  cover: { width: '100%', height: 120 },
  body: { paddingHorizontal: spacing.lg },
  header: { flexDirection: 'row', alignItems: 'flex-start', gap: spacing.md },
  avatar: {
    width: 84,
    height: 84,
    borderRadius: 42,
    backgroundColor: colors.surface,
    borderWidth: 3,
    borderColor: colors.white,
    marginTop: -34,
  },
  avatarFallback: { alignItems: 'center', justifyContent: 'center', backgroundColor: colors.primaryBg },
  name: { fontSize: 20, fontWeight: '800', color: colors.text },
  meta: { fontSize: 13, color: colors.textMuted, marginTop: 2 },
  pills: { flexDirection: 'row', gap: spacing.xs, marginTop: 6 },
  pill: {
    backgroundColor: colors.primaryBg,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: radius.pill,
  },
  pillFirm: { backgroundColor: '#EDE9FE' },
  pillText: { fontSize: 10, fontWeight: '800', color: colors.primaryDark },
  pillTextFirm: { color: '#6D28D9' },
  ref: { fontSize: 11, color: colors.textLight, marginTop: spacing.md },

  section: { marginTop: spacing.xl },
  sectionTitle: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.textLight,
    letterSpacing: 0.6,
    textTransform: 'uppercase',
    marginBottom: spacing.sm,
  },
  bio: { fontSize: 15, color: colors.textMuted, lineHeight: 22 },
  chips: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.xs },
  chip: {
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: radius.pill,
  },
  chipText: { fontSize: 12, color: colors.textMuted },
  rateRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: spacing.sm,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  rateLabel: { fontSize: 14, color: colors.textMuted },
  rateValue: { fontSize: 14, fontWeight: '700', color: colors.text },

  footer: {
    flexDirection: 'row',
    gap: spacing.sm,
    padding: spacing.lg,
    borderTopWidth: 1,
    borderTopColor: colors.border,
    backgroundColor: colors.white,
  },
  callBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    paddingHorizontal: spacing.xl,
    borderRadius: radius.pill,
    borderWidth: 1.5,
    borderColor: colors.primary,
  },
  callText: { color: colors.primary, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
  mailBtn: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
  },
  mailText: { color: colors.white, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
});
