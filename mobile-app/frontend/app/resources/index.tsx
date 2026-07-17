import { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  FlatList,
  Modal,
  StyleSheet,
  Pressable,
  ActivityIndicator,
  Linking,
  Alert,
  KeyboardAvoidingView,
  Platform,
  RefreshControl,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Stack, useRouter } from 'expo-router';
import { resourcesApi } from '../../src/api/endpoints';
import { useRefresh } from '../../src/hooks/useRefresh';
import { useInfiniteList } from '../../src/hooks/useInfiniteList';
import { ListFooter } from '../../src/components/ListFooter';
import { SkeletonList, ListRowSkeleton } from '../../src/components/skeletons';
import { colors, radius, spacing } from '../../src/theme/colors';
import type { PastPaper, VideoSolution } from '../../src/types';

type Tab = 'papers' | 'videos';

export default function Resources() {
  const [tab, setTab] = useState<Tab>('papers');

  return (
    <SafeAreaView style={styles.safe} edges={['bottom']}>
      <Stack.Screen options={{ title: 'Resources', headerShown: true }} />
      <View style={styles.segment}>
        <Seg label="Past Papers" active={tab === 'papers'} onPress={() => setTab('papers')} />
        <Seg label="Video Solutions" active={tab === 'videos'} onPress={() => setTab('videos')} />
      </View>
      {tab === 'papers' ? <Papers /> : <Videos />}
    </SafeAreaView>
  );
}

function Papers() {
  const q = useInfiniteList(['resources', 'papers'], (page) => resourcesApi.pastPapers({ page }));
  const [openingId, setOpeningId] = useState<number | null>(null);
  const [buyPaper, setBuyPaper] = useState<PastPaper | null>(null);
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  const openPaper = async (paper: PastPaper) => {
    if (paper.isPaid) {
      setBuyPaper(paper);
      return;
    }
    setOpeningId(paper.id);
    try {
      const full = await resourcesApi.pastPaper(paper.id);
      if (full.fileUrl) await Linking.openURL(full.fileUrl);
      else Alert.alert('Unavailable', 'This file is not available right now.');
    } catch (e) {
      Alert.alert('Error', (e as Error).message);
    } finally {
      setOpeningId(null);
    }
  };

  if (q.isLoading) return <Loading />;
  return (
    <>
    <FlatList
      data={q.items}
      keyExtractor={(p) => String(p.id)}
      contentContainerStyle={styles.list}
      onEndReached={q.loadMore}
      onEndReachedThreshold={0.5}
      ListFooterComponent={<ListFooter loadingMore={q.loadingMore} hasMore={q.hasMore} count={q.items.length} />}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
      }
      renderItem={({ item }) => (
        <Pressable style={styles.card} onPress={() => openPaper(item)}>
          <View style={styles.iconBox}>
            <Ionicons name="document-text" size={22} color={colors.primary} />
          </View>
          <View style={{ flex: 1 }}>
            <Text style={styles.cardTitle} numberOfLines={2}>
              {item.title || `${item.subject} ${item.year}`}
            </Text>
            <Text style={styles.cardMeta}>
              {item.examBody} · {item.subject} · {item.year}
              {item.fileSize ? ` · ${item.fileSize}` : ''}
            </Text>
          </View>
          {item.isPaid ? (
            <View style={styles.priceTag}>
              <Ionicons name="lock-closed" size={12} color={colors.white} />
              <Text style={styles.priceText}>MWK {item.price.toLocaleString()}</Text>
            </View>
          ) : openingId === item.id ? (
            <ActivityIndicator color={colors.primary} />
          ) : (
            <Ionicons name="download-outline" size={22} color={colors.accent} />
          )}
        </Pressable>
      )}
      ListEmptyComponent={<Empty label="No past papers yet." />}
    />
    <BuyPaperSheet paper={buyPaper} onClose={() => setBuyPaper(null)} />
    </>
  );
}

function BuyPaperSheet({ paper, onClose }: { paper: PastPaper | null; onClose: () => void }) {
  const router = useRouter();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [busy, setBusy] = useState(false);

  const reset = () => {
    setName('');
    setEmail('');
    setPhone('');
    setBusy(false);
  };

  const submit = async () => {
    if (!paper) return;
    if (!name.trim() || !email.trim()) {
      Alert.alert('Missing details', 'Please enter your name and email.');
      return;
    }
    setBusy(true);
    try {
      const res = await resourcesApi.purchasePaper(paper.id, {
        buyerName: name.trim(),
        buyerEmail: email.trim(),
        buyerPhone: phone.trim() || undefined,
      });
      if (!res.checkoutUrl) {
        Alert.alert('Unavailable', 'Could not start the payment. Please try again later.');
        setBusy(false);
        return;
      }
      const url = res.checkoutUrl;
      const txRef = res.txRef;
      reset();
      onClose();
      router.push({ pathname: '/resources/checkout', params: { url, txRef } });
    } catch (e) {
      Alert.alert('Payment error', (e as Error).message);
      setBusy(false);
    }
  };

  return (
    <Modal visible={!!paper} transparent animationType="slide" onRequestClose={onClose}>
      <KeyboardAvoidingView
        style={{ flex: 1, justifyContent: 'flex-end' }}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      >
      <Pressable style={styles.backdrop} onPress={onClose} />
      <View style={styles.sheet}>
        <View style={styles.sheetHandle} />
        <Text style={styles.sheetTitle}>Buy this paper</Text>
        {paper && (
          <Text style={styles.sheetMeta}>
            {paper.title || `${paper.subject} ${paper.year}`} · MWK {paper.price.toLocaleString()}
          </Text>
        )}
        <Text style={styles.sheetLabel}>Full name</Text>
        <TextInput style={styles.sheetInput} value={name} onChangeText={setName} placeholder="Your name" placeholderTextColor={colors.textLight} />
        <Text style={styles.sheetLabel}>Email (download link is tied to this)</Text>
        <TextInput style={styles.sheetInput} value={email} onChangeText={setEmail} placeholder="you@example.com" placeholderTextColor={colors.textLight} keyboardType="email-address" autoCapitalize="none" />
        <Text style={styles.sheetLabel}>Phone (optional)</Text>
        <TextInput style={styles.sheetInput} value={phone} onChangeText={setPhone} placeholder="+265…" placeholderTextColor={colors.textLight} keyboardType="phone-pad" />

        <Pressable style={[styles.payBtn, busy && { opacity: 0.7 }]} onPress={submit} disabled={busy}>
          {busy ? (
            <ActivityIndicator color={colors.white} />
          ) : (
            <>
              <Ionicons name="card" size={18} color={colors.white} />
              <Text style={styles.payText}>Pay with PayChangu</Text>
            </>
          )}
        </Pressable>
        <Pressable onPress={onClose} style={styles.cancel}>
          <Text style={styles.cancelText}>Cancel</Text>
        </Pressable>
      </View>
      </KeyboardAvoidingView>
    </Modal>
  );
}

function Videos() {
  const q = useInfiniteList(['resources', 'videos'], (page) => resourcesApi.videos({ page }));
  const { refreshing, onRefresh } = useRefresh(q.refetch);

  const openVideo = (v: VideoSolution) => {
    let url: string | null = null;
    if (v.platform === 'youtube' && v.videoId) url = `https://www.youtube.com/watch?v=${v.videoId}`;
    else if (v.platform === 'vimeo' && v.videoId) url = `https://vimeo.com/${v.videoId}`;
    if (url) Linking.openURL(url);
    else Alert.alert('Unavailable', 'This video cannot be opened.');
  };

  if (q.isLoading) return <Loading />;
  return (
    <FlatList
      data={q.items}
      keyExtractor={(v) => String(v.id)}
      contentContainerStyle={styles.list}
      onEndReached={q.loadMore}
      onEndReachedThreshold={0.5}
      ListFooterComponent={<ListFooter loadingMore={q.loadingMore} hasMore={q.hasMore} count={q.items.length} />}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} colors={[colors.primary]} />
      }
      renderItem={({ item }) => (
        <Pressable style={styles.card} onPress={() => openVideo(item)}>
          <View style={[styles.iconBox, { backgroundColor: '#FEE2E2' }]}>
            <Ionicons name="play" size={22} color={colors.danger} />
          </View>
          <View style={{ flex: 1 }}>
            <Text style={styles.cardTitle} numberOfLines={2}>
              {item.title}
            </Text>
            <Text style={styles.cardMeta}>
              {[item.subject, item.examBody, item.tutorName].filter(Boolean).join(' · ')}
            </Text>
          </View>
          <Ionicons name="open-outline" size={20} color={colors.accent} />
        </Pressable>
      )}
      ListEmptyComponent={<Empty label="No video solutions yet." />}
    />
  );
}

function Seg({ label, active, onPress }: { label: string; active: boolean; onPress: () => void }) {
  return (
    <Pressable style={[styles.seg, active && styles.segActive]} onPress={onPress}>
      <Text style={[styles.segText, active && styles.segTextActive]}>{label}</Text>
    </Pressable>
  );
}

const Loading = () => (
  <View style={styles.list}>
    <SkeletonList count={7} Item={ListRowSkeleton} gap={spacing.sm} />
  </View>
);
const Empty = ({ label }: { label: string }) => <Text style={styles.empty}>{label}</Text>;

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: colors.bg },
  segment: { flexDirection: 'row', gap: spacing.sm, padding: spacing.lg },
  seg: {
    flex: 1,
    paddingVertical: spacing.md,
    borderRadius: radius.pill,
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
  },
  segActive: { backgroundColor: colors.primary, borderColor: colors.primary },
  segText: { fontWeight: '700', color: colors.textMuted },
  segTextActive: { color: colors.white },
  list: { paddingHorizontal: spacing.lg, gap: spacing.sm, paddingBottom: spacing.xxl },
  card: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.md,
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    borderWidth: 1,
    borderColor: colors.border,
    padding: spacing.md,
  },
  iconBox: {
    width: 44,
    height: 44,
    borderRadius: radius.md,
    backgroundColor: colors.surface,
    justifyContent: 'center',
    alignItems: 'center',
  },
  cardTitle: { fontSize: 15, fontWeight: '700', color: colors.text },
  cardMeta: { fontSize: 12, color: colors.textMuted, marginTop: 2 },
  priceTag: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: colors.primary,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: radius.pill,
  },
  priceText: { color: colors.white, fontSize: 11, fontWeight: '700' },
  empty: { color: colors.textMuted, textAlign: 'center', marginTop: spacing.xxl },
  backdrop: { flex: 1, backgroundColor: 'rgba(0,0,0,0.4)' },
  sheet: {
    backgroundColor: colors.white,
    borderTopLeftRadius: radius.xl,
    borderTopRightRadius: radius.xl,
    padding: spacing.lg,
    paddingBottom: spacing.xxl,
  },
  sheetHandle: {
    width: 40,
    height: 4,
    borderRadius: 2,
    backgroundColor: colors.border,
    alignSelf: 'center',
    marginBottom: spacing.md,
  },
  sheetTitle: { fontSize: 18, fontWeight: '800', color: colors.text },
  sheetMeta: { fontSize: 13, color: colors.textMuted, marginTop: 2, marginBottom: spacing.md },
  sheetLabel: { fontSize: 13, fontWeight: '600', color: colors.text, marginTop: spacing.sm, marginBottom: 4 },
  sheetInput: {
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: radius.md,
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    fontSize: 15,
    color: colors.text,
    backgroundColor: colors.surface,
  },
  payBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    marginTop: spacing.lg,
  },
  payText: { color: colors.white, fontWeight: '700', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
  cancel: { alignItems: 'center', marginTop: spacing.md },
  cancelText: { color: colors.textMuted, fontWeight: '600' },
});
