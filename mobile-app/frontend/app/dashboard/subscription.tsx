import { ScrollView, View, Text, StyleSheet, Pressable, ActivityIndicator, Alert, RefreshControl } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { meApi, plansApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { Plan } from '../../src/types';

export default function SubscriptionScreen() {
  const qc = useQueryClient();
  const router = useRouter();
  const sub = useQuery({ queryKey: ['me', 'subscription'], queryFn: meApi.subscription });
  const plans = useQuery({ queryKey: ['plans'], queryFn: plansApi.list });
  const { refreshing, onRefresh } = useRefresh(sub.refetch, plans.refetch);

  const checkout = useMutation({
    mutationFn: (planId: number) => meApi.checkout(planId, 1),
    onSuccess: (res) => {
      if (res.free) {
        qc.invalidateQueries({ queryKey: ['me', 'subscription'] });
        Alert.alert('Activated', 'Your free plan is now active.');
      } else if (res.checkoutUrl) {
        router.push({
          pathname: '/dashboard/checkout',
          params: { url: res.checkoutUrl, txRef: res.txRef },
        });
      } else {
        Alert.alert(
          'Payment started',
          `Reference ${res.txRef}. Complete the payment, then pull to refresh.`
        );
      }
    },
    onError: (e) => Alert.alert('Error', (e as Error).message),
  });

  const currentPlanId = sub.data?.planId;

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Subscription', headerShown: true }} />
      <ScrollView
        contentContainerStyle={styles.content}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
        }
      >
        {sub.isLoading ? (
          <ActivityIndicator color={colors.primary} />
        ) : sub.data ? (
          <View style={styles.current}>
            <Text style={styles.currentLabel}>Current plan</Text>
            <Text style={styles.currentPlan}>{sub.data.planName}</Text>
            <View style={[styles.statusTag, sub.data.isActive ? styles.active : styles.inactive]}>
              <Text style={styles.statusText}>
                {sub.data.isActive ? 'Active' : sub.data.status} · until{' '}
                {sub.data.currentPeriodEnd?.slice(0, 10)}
              </Text>
            </View>
          </View>
        ) : (
          <Text style={styles.muted}>No active subscription.</Text>
        )}

        <Text style={styles.heading}>Plans</Text>
        {plans.data?.map((p) => (
          <PlanCard
            key={p.id}
            plan={p}
            current={p.id === currentPlanId}
            busy={checkout.isPending}
            onSelect={() => checkout.mutate(p.id)}
          />
        ))}
      </ScrollView>
    </SafeAreaView>
  );
}

function PlanCard({
  plan,
  current,
  busy,
  onSelect,
}: {
  plan: Plan;
  current: boolean;
  busy: boolean;
  onSelect: () => void;
}) {
  return (
    <View style={[styles.card, current && styles.cardCurrent]}>
      <View style={styles.cardHead}>
        <Text style={styles.planName}>{plan.name}</Text>
        <Text style={styles.price}>
          {plan.priceMonthly > 0 ? `MWK ${plan.priceMonthly.toLocaleString()}/mo` : 'Free'}
        </Text>
      </View>
      {plan.description ? <Text style={styles.desc}>{plan.description}</Text> : null}
      <View style={styles.features}>
        {plan.features.showWhatsapp && <Feature text="WhatsApp contact" />}
        {plan.features.maxSubjects > 0 && <Feature text={`Up to ${plan.features.maxSubjects} subjects`} />}
        {plan.features.allowVideoUpload && <Feature text="Video uploads" />}
        {plan.features.allowAnnouncements && <Feature text="Announcements" />}
        {plan.searchRanking !== 'low' && <Feature text={`${plan.searchRanking} search ranking`} />}
      </View>
      {current ? (
        <View style={styles.currentBadge}>
          <Text style={styles.currentBadgeText}>Current plan</Text>
        </View>
      ) : (
        <Pressable style={[styles.selectBtn, busy && { opacity: 0.6 }]} onPress={onSelect} disabled={busy}>
          <Text style={styles.selectText}>{plan.priceMonthly > 0 ? 'Subscribe' : 'Activate'}</Text>
        </Pressable>
      )}
    </View>
  );
}

function Feature({ text }: { text: string }) {
  return (
    <View style={styles.featureRow}>
      <Ionicons name="checkmark-circle" size={15} color={colors.success} />
      <Text style={styles.featureText}>{text}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  content: { padding: spacing.lg, paddingBottom: spacing.xxl },
  current: {
    backgroundColor: colors.primary,
    borderRadius: radius.lg,
    padding: spacing.lg,
    gap: spacing.xs,
  },
  currentLabel: { color: 'rgba(255,255,255,0.8)', fontSize: 13 },
  currentPlan: { color: colors.white, fontSize: 24, fontWeight: '800' },
  statusTag: { alignSelf: 'flex-start', paddingHorizontal: 10, paddingVertical: 4, borderRadius: radius.pill, marginTop: spacing.xs },
  active: { backgroundColor: 'rgba(255,255,255,0.25)' },
  inactive: { backgroundColor: 'rgba(0,0,0,0.2)' },
  statusText: { color: colors.white, fontSize: 12, fontWeight: '600' },
  muted: { color: colors.textMuted },
  heading: { fontSize: 18, fontWeight: '800', color: colors.text, marginTop: spacing.xl, marginBottom: spacing.md },
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.lg,
    marginBottom: spacing.md,
    gap: spacing.sm,
  },
  cardCurrent: { borderColor: colors.primary, borderWidth: 2 },
  cardHead: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  planName: { fontSize: 18, fontWeight: '700', color: colors.text },
  price: { fontSize: 15, fontWeight: '700', color: colors.primary },
  desc: { color: colors.textMuted, fontSize: 14 },
  features: { gap: 4, marginTop: spacing.xs },
  featureRow: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  featureText: { color: colors.textMuted, fontSize: 13 },
  selectBtn: {
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.md,
    alignItems: 'center',
    marginTop: spacing.sm,
  },
  selectText: { color: colors.white, fontWeight: '700' },
  currentBadge: { alignItems: 'center', paddingVertical: spacing.md, marginTop: spacing.sm },
  currentBadgeText: { color: colors.success, fontWeight: '700' },
});
