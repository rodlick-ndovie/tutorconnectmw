import { useEffect, useRef, useState } from 'react';
import {
  Animated,
  View,
  Text,
  StyleSheet,
  Pressable,
  ScrollView,
  useWindowDimensions,
  type NativeSyntheticEvent,
  type NativeScrollEvent,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { StatusBar } from 'expo-status-bar';
import { Image } from 'expo-image';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import { colors, radius, spacing } from '../src/theme/colors';
import { markOnboardingSeen } from '../src/lib/onboarding';

const SLIDES = [
  {
    image: require('../assets/onboarding/tutor.jpg'),
    tag: 'Verified teachers',
    title: 'Find the perfect teacher',
    body: 'Discover verified tutors across Malawi for every subject, curriculum and level — from primary to secondary.',
  },
  {
    image: require('../assets/onboarding/learn.jpg'),
    tag: 'Direct & instant',
    title: 'Connect in one tap',
    body: 'Reach a tutor instantly by WhatsApp, phone call or email. No account needed to browse and connect.',
  },
  {
    image: require('../assets/onboarding/grow.jpg'),
    tag: 'Grow your practice',
    title: 'Are you a teacher?',
    body: 'Build your profile, showcase your subjects and reach thousands of students looking for a tutor like you.',
  },
];

export default function Onboarding() {
  const router = useRouter();
  const { width } = useWindowDimensions();
  const scrollRef = useRef<ScrollView>(null);
  const [index, setIndex] = useState(0);
  const isLast = index === SLIDES.length - 1;
  const slide = SLIDES[index];

  // Fade the caption block in each time the slide changes.
  const fade = useRef(new Animated.Value(0)).current;
  useEffect(() => {
    fade.setValue(0);
    Animated.timing(fade, {
      toValue: 1,
      duration: 350,
      useNativeDriver: true,
    }).start();
  }, [index, fade]);

  const onScroll = (e: NativeSyntheticEvent<NativeScrollEvent>) => {
    const i = Math.round(e.nativeEvent.contentOffset.x / width);
    if (i !== index) setIndex(i);
  };

  const next = () => {
    if (isLast) return;
    scrollRef.current?.scrollTo({ x: width * (index + 1), animated: true });
    setIndex(index + 1);
  };

  const finish = async (destination: '/(tabs)' | '/login') => {
    await markOnboardingSeen();
    router.replace(destination);
  };

  return (
    <View style={styles.root}>
      <StatusBar style="light" />

      <ScrollView
        ref={scrollRef}
        horizontal
        pagingEnabled
        showsHorizontalScrollIndicator={false}
        onMomentumScrollEnd={onScroll}
      >
        {SLIDES.map((s) => (
          <View key={s.title} style={{ width }}>
            <Image source={s.image} style={StyleSheet.absoluteFill} contentFit="cover" transition={250} />
            <LinearGradient
              colors={['rgba(15,23,42,0.15)', 'rgba(15,23,42,0.55)', 'rgba(20,28,42,0.96)']}
              locations={[0, 0.45, 1]}
              style={StyleSheet.absoluteFill}
            />
          </View>
        ))}
      </ScrollView>

      {/* Top bar */}
      <SafeAreaView style={styles.topBar} edges={['top']} pointerEvents="box-none">
        {!isLast && (
          <Pressable onPress={() => finish('/(tabs)')} hitSlop={10} style={styles.skipBtn}>
            <Text style={styles.skip}>Skip</Text>
          </Pressable>
        )}
      </SafeAreaView>

      {/* Bottom content overlay */}
      <SafeAreaView style={styles.bottom} edges={['bottom']} pointerEvents="box-none">
        <Animated.View style={{ opacity: fade }}>
          <View style={styles.tagChip}>
            <Text style={styles.tagText}>{slide.tag}</Text>
          </View>
          <Text style={styles.title}>{slide.title}</Text>
          <Text style={styles.body}>{slide.body}</Text>
        </Animated.View>

        <View style={styles.dots}>
          {SLIDES.map((_, i) => (
            <View key={i} style={[styles.dot, i === index && styles.dotActive]} />
          ))}
        </View>

        {isLast ? (
          <View style={{ gap: spacing.md }}>
            <Pressable style={styles.primaryBtn} onPress={() => finish('/(tabs)')}>
              <Ionicons name="search" size={18} color={colors.white} />
              <Text style={styles.primaryText}>I'm a Student — Browse tutors</Text>
            </Pressable>
            <Pressable style={styles.secondaryBtn} onPress={() => finish('/login')}>
              <Ionicons name="school-outline" size={18} color={colors.white} />
              <Text style={styles.secondaryText}>I'm a Teacher — Log in</Text>
            </Pressable>
          </View>
        ) : (
          <Pressable style={styles.primaryBtn} onPress={next}>
            <Text style={styles.primaryText}>Next</Text>
            <Ionicons name="arrow-forward" size={18} color={colors.white} />
          </Pressable>
        )}
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.secondary },
  topBar: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    flexDirection: 'row',
    justifyContent: 'flex-end',
    alignItems: 'center',
    paddingHorizontal: spacing.lg,
    paddingTop: spacing.sm,
  },
  skipBtn: {
    backgroundColor: 'rgba(255,255,255,0.18)',
    paddingHorizontal: spacing.md,
    paddingVertical: 6,
    borderRadius: radius.pill,
  },
  skip: { color: colors.white, fontSize: 14, fontWeight: '700' },
  bottom: {
    position: 'absolute',
    left: 0,
    right: 0,
    bottom: 0,
    paddingHorizontal: spacing.xl,
    paddingBottom: spacing.lg,
  },
  tagChip: {
    alignSelf: 'flex-start',
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingHorizontal: spacing.md,
    paddingVertical: 5,
    marginBottom: spacing.md,
  },
  tagText: { color: colors.white, fontSize: 12, fontWeight: '800', letterSpacing: 0.3 },
  title: { color: colors.white, fontSize: 32, fontWeight: '800', lineHeight: 38 },
  body: { color: 'rgba(255,255,255,0.82)', fontSize: 16, lineHeight: 24, marginTop: spacing.sm },
  dots: { flexDirection: 'row', gap: spacing.xs, marginTop: spacing.xl, marginBottom: spacing.lg },
  dot: { width: 8, height: 8, borderRadius: 4, backgroundColor: 'rgba(255,255,255,0.35)' },
  dotActive: { backgroundColor: colors.primary, width: 24 },
  primaryBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.sm,
    backgroundColor: colors.primary,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
  },
  primaryText: { color: colors.white, fontWeight: '800', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
  secondaryBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: spacing.sm,
    borderRadius: radius.pill,
    paddingVertical: spacing.lg,
    borderWidth: 1.5,
    borderColor: 'rgba(255,255,255,0.5)',
    backgroundColor: 'rgba(255,255,255,0.08)',
  },
  secondaryText: { color: colors.white, fontWeight: '800', fontSize: 16 , textAlign: 'center', paddingHorizontal: 2},
});
