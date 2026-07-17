import {
  ScrollView,
  View,
  Text,
  StyleSheet,
  Pressable,
  ActivityIndicator,
  Alert,
  RefreshControl,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { universityApi } from '../../src/api/endpoints';
import { ErrorState } from '../../src/components/ErrorState';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useAuth } from '../../src/store/auth';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { LectureRequest } from '../../src/types';

export default function UniversityPortal() {
  const router = useRouter();
  const qc = useQueryClient();
  const { status } = useAuth();

  const profile = useQuery({
    queryKey: ['university', 'me'],
    queryFn: universityApi.me,
    enabled: status === 'authenticated',
    retry: false,
  });
  const requests = useQuery({
    queryKey: ['university', 'open-requests'],
    queryFn: () => universityApi.openRequests(),
    enabled: status === 'authenticated',
  });
  const { refreshing, onRefresh } = useRefresh(profile.refetch, requests.refetch);

  const apply = useMutation({
    mutationFn: (id: number) => universityApi.applyToRequest(id),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['university', 'open-requests'] });
      Alert.alert('Applied', 'The student will be notified that you can help.');
    },
    onError: (e) => Alert.alert('Could not apply', (e as Error).message),
  });

  if (status !== 'authenticated') {
    return (
      <SafeAreaView style={styles.center}>
        <Stack.Screen options={{ title: 'University Portal', headerShown: true }} />
        <Ionicons name="lock-closed-outline" size={44} color={colors.textLight} />
        <Text style={styles.gateTitle}>Log in to your portal</Text>
        <Text style={styles.gateHint}>
          The university portal is for registered university tutors and companies.
        </Text>
        <Pressable style={styles.gateBtn} onPress={() => router.push('/login')}>
          <Text style={styles.gateBtnText}>Log In</Text>
        </Pressable>
        <Pressable onPress={() => router.push('/university/register')}>
          <Text style={styles.gateLink}>Not registered? Join University Support</Text>
        </Pressable>
      </SafeAreaView>
    );
  }

  if (profile.isLoading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  // Signed in, but no university profile attached to this account.
  if (profile.isError || !profile.data) {
    return (
      <SafeAreaView style={styles.center}>
        <Stack.Screen options={{ title: 'University Portal', headerShown: true }} />
        <Ionicons name="school-outline" size={44} color={colors.textLight} />
        <Text style={styles.gateTitle}>No university profile</Text>
        <Text style={styles.gateHint}>
          This account isn't registered for University & College Support yet.
        </Text>
        <Pressable style={styles.gateBtn} onPress={() => router.push('/university/register')}>
          <Text style={styles.gateBtnText}>Join now</Text>
        </Pressable>
      </SafeAreaView>
    );
  }

  const me = profile.data;
  const approved = me.status === 'approved';

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'University Portal', headerShown: true }} />
      <ScrollView
        contentContainerStyle={styles.content}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
      >
        <View style={styles.card}>
          <View style={styles.cardHead}>
            <View style={styles.iconWrap}>
              <Ionicons
                name={me.accountType === 'firm' ? 'business' : 'school'}
                size={20}
                color={colors.primary}
              />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={styles.name}>{me.name}</Text>
              <Text style={styles.meta}>
                {me.accountType === 'firm' ? 'Company' : 'University Tutor'} · {me.subscriptionPlan}
              </Text>
            </View>
            <View style={[styles.status, approved ? styles.statusOk : styles.statusPending]}>
              <Text style={[styles.statusText, approved ? { color: colors.success } : { color: '#B45309' }]}>
                {approved ? 'Approved' : 'Pending'}
              </Text>
            </View>
          </View>
          <Text style={styles.ref}>Ref: {me.referenceCode}</Text>
        </View>

        {!approved && (
          <View style={styles.notice}>
            <Ionicons name="information-circle" size={20} color="#B45309" />
            <Text style={styles.noticeText}>
              Your profile is awaiting admin review. Once approved you'll appear in the directory and
              can apply to lecture requests.
            </Text>
          </View>
        )}

        <Text style={styles.sectionTitle}>Open lecture requests</Text>
        {requests.isLoading ? (
          <ActivityIndicator color={colors.primary} style={{ marginVertical: spacing.lg }} />
        ) : requests.isError ? (
          <ErrorState compact message={(requests.error as Error)?.message} onRetry={() => requests.refetch()} />
        ) : (requests.data?.items.length ?? 0) === 0 ? (
          <Text style={styles.empty}>No open requests right now.</Text>
        ) : (
          requests.data!.items.map((r) => (
            <RequestCard
              key={r.id}
              request={r}
              canApply={approved}
              applying={apply.isPending && apply.variables === r.id}
              onApply={() => apply.mutate(r.id)}
            />
          ))
        )}
      </ScrollView>
    </SafeAreaView>
  );
}

function RequestCard({
  request,
  canApply,
  applying,
  onApply,
}: {
  request: LectureRequest;
  canApply: boolean;
  applying: boolean;
  onApply: () => void;
}) {
  return (
    <View style={styles.reqCard}>
      <Text style={styles.reqTopic}>{request.topic}</Text>
      <Text style={styles.reqMeta}>
        {[request.serviceCategory, request.institution, request.cityLocation, request.deliveryMode]
          .filter(Boolean)
          .join(' · ')}
      </Text>
      {request.budgetRange && <Text style={styles.reqBudget}>Budget: {request.budgetRange}</Text>}
      {request.notes && (
        <Text style={styles.reqNotes} numberOfLines={2}>
          {request.notes}
        </Text>
      )}
      <Pressable
        style={[styles.applyBtn, (!canApply || applying) && { opacity: 0.5 }]}
        onPress={onApply}
        disabled={!canApply || applying}
      >
        {applying ? (
          <ActivityIndicator color={colors.white} size="small" />
        ) : (
          <Text style={styles.applyText}>{canApply ? 'Apply' : 'Approval required'}</Text>
        )}
      </Pressable>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: colors.bg,
    gap: spacing.sm,
    padding: spacing.xl,
  },
  gateTitle: { fontSize: 18, fontWeight: '800', color: colors.text, marginTop: spacing.sm },
  gateHint: { fontSize: 14, color: colors.textMuted, textAlign: 'center' },
  gateBtn: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
    paddingHorizontal: spacing.xxl,
    marginTop: spacing.md,
  },
  gateBtnText: { color: colors.white, fontWeight: '800', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
  gateLink: { color: colors.primary, fontWeight: '600', marginTop: spacing.md },

  content: { padding: spacing.lg, paddingBottom: spacing.xxl },
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
  },
  cardHead: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  iconWrap: {
    width: 42,
    height: 42,
    borderRadius: 21,
    backgroundColor: colors.primaryBg,
    alignItems: 'center',
    justifyContent: 'center',
  },
  name: { fontSize: 16, fontWeight: '800', color: colors.text },
  meta: { fontSize: 12, color: colors.textMuted, marginTop: 1 },
  status: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: radius.pill },
  statusOk: { backgroundColor: colors.successBg },
  statusPending: { backgroundColor: '#FEF3C7' },
  statusText: { fontSize: 11, fontWeight: '800' },
  ref: { fontSize: 11, color: colors.textLight, marginTop: spacing.sm },

  notice: {
    flexDirection: 'row',
    gap: spacing.sm,
    backgroundColor: '#FEF3C7',
    borderRadius: radius.md,
    padding: spacing.md,
    marginTop: spacing.md,
  },
  noticeText: { flex: 1, fontSize: 13, color: '#92400E', lineHeight: 18 },

  sectionTitle: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.textLight,
    letterSpacing: 0.6,
    textTransform: 'uppercase',
    marginTop: spacing.xl,
    marginBottom: spacing.sm,
  },
  empty: { color: colors.textMuted, textAlign: 'center', marginVertical: spacing.lg },

  reqCard: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
    marginBottom: spacing.md,
    gap: 4,
  },
  reqTopic: { fontSize: 15, fontWeight: '700', color: colors.text },
  reqMeta: { fontSize: 12, color: colors.textMuted },
  reqBudget: { fontSize: 12, color: colors.primaryDark, fontWeight: '700', marginTop: 2 },
  reqNotes: { fontSize: 13, color: colors.textMuted, marginTop: 2 },
  applyBtn: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.sm,
    alignItems: 'center',
    marginTop: spacing.sm,
  },
  applyText: { color: colors.white, fontWeight: '800', fontSize: 14 , textAlign: 'center', paddingHorizontal: 2},
});
