import { useEffect, useState } from 'react';
import { View, ActivityIndicator, StyleSheet } from 'react-native';
import { Redirect } from 'expo-router';
import { hasSeenOnboarding } from '../src/lib/onboarding';
import { colors } from '../src/theme/colors';

// Entry gate: show the onboarding intro on first launch, then the tabs.
export default function Index() {
  const [seen, setSeen] = useState<boolean | null>(null);

  useEffect(() => {
    hasSeenOnboarding().then(setSeen);
  }, []);

  if (seen === null) {
    return (
      <View style={styles.center}>
        <ActivityIndicator color={colors.primary} size="large" />
      </View>
    );
  }
  return <Redirect href={seen ? '/(tabs)' : '/onboarding'} />;
}

const styles = StyleSheet.create({
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg },
});
