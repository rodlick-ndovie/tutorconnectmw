import { View, Text, StyleSheet, Pressable } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import { colors, radius, spacing } from '../theme/colors';
import { Avatar } from './Avatar';
import { RatingStars } from './RatingStars';
import { useFavorites } from '../store/favorites';
import type { TutorCard as TutorCardType } from '../types';


export function TutorCard({ tutor, width }: { tutor: TutorCardType; width?: number }) {
  const router = useRouter();
  const isFavorite = useFavorites((s) => s.isFavorite(tutor.id));
  const toggle = useFavorites((s) => s.toggle);

  return (
    <Pressable
      style={[styles.card, width ? { width } : undefined]}
      onPress={() => router.push(`/tutor/${tutor.id}`)}
    >
      <View style={styles.header}>
        <Avatar uri={tutor.profilePicture} name={tutor.name} size={56} />
        <View style={styles.headerText}>
          <Text style={styles.name} numberOfLines={1}>
            {tutor.name}
          </Text>
          <RatingStars rating={tutor.rating} reviewCount={tutor.reviewCount} />
        </View>
        <Pressable hitSlop={8} onPress={() => toggle(tutor)}>
          <Ionicons
            name={isFavorite ? 'heart' : 'heart-outline'}
            size={22}
            color={isFavorite ? colors.danger : colors.textLight}
          />
        </Pressable>
      </View>

      <View style={styles.badges}>
        {tutor.featured && <Badge label="Featured" />}
        {/* Plan name is intentionally NOT shown — a tutor's subscription tier is
            private and shouldn't be advertised on the public list. */}
        {tutor.curricula.slice(0, 1).map((c) => (
          <Badge key={c} label={c} />
        ))}
      </View>

      <View style={styles.facts}>
        {tutor.experienceYears ? <Fact text={`${tutor.experienceYears} years experience`} /> : null}
        {tutor.schoolName ? <Fact text={tutor.schoolName} /> : null}
        {tutor.subjects.length > 0 ? <Fact text={tutor.subjects.slice(0, 3).join(', ')} /> : null}
        {tutor.district ? <Fact text={tutor.district} /> : null}
      </View>

      <Pressable style={styles.viewBtn} onPress={() => router.push(`/tutor/${tutor.id}`)}>
        <Text style={styles.viewText}>View Profile</Text>
      </Pressable>
    </Pressable>
  );
}

function Badge({ label }: { label: string }) {
  return (
    <View style={styles.badge}>
      <Text style={styles.badgeText}>{label}</Text>
    </View>
  );
}

function Fact({ text }: { text: string }) {
  return (
    <View style={styles.factRow}>
      <Ionicons name="checkmark-circle" size={15} color={colors.success} />
      <Text style={styles.factText} numberOfLines={1}>
        {text}
      </Text>
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: colors.white,
    borderRadius: radius.lg,
    padding: spacing.lg,
    borderWidth: 1,
    borderColor: colors.border,
    gap: spacing.sm,
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 8,
    shadowOffset: { width: 0, height: 2 },
    elevation: 2,
  },
  header: { flexDirection: 'row', alignItems: 'center', gap: spacing.md },
  avatar: { width: 56, height: 56, borderRadius: 28, backgroundColor: colors.surface },
  headerText: { flex: 1, gap: 2 },
  name: { fontSize: 17, fontWeight: '700', color: colors.text },
  badges: { flexDirection: 'row', flexWrap: 'wrap', gap: spacing.xs },
  badge: {
    backgroundColor: colors.primaryBg,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: radius.pill,
  },
  badgeText: { color: colors.primaryDark, fontSize: 11, fontWeight: '700' },
  facts: { gap: 4, marginTop: spacing.xs },
  factRow: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  factText: { color: colors.textMuted, fontSize: 13, flex: 1 },
  viewBtn: {
    backgroundColor: colors.accent,
    borderRadius: radius.pill,
    paddingVertical: 10,
    alignItems: 'center',
    marginTop: spacing.sm,
  },
  viewText: { color: colors.white, fontWeight: '700', fontSize: 15 , textAlign: 'center', paddingHorizontal: 2},
});
