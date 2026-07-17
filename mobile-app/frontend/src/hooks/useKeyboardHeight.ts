import { useEffect, useState } from 'react';
import { Keyboard, Platform } from 'react-native';

/**
 * Current on-screen keyboard height (0 when hidden).
 *
 * We can't rely on Android's windowSoftInputMode="resize": in Expo Go the host
 * app owns the manifest, so `android.softwareKeyboardLayoutMode` in app.json is
 * ignored and the window never shrinks — leaving inputs stranded under the
 * keyboard with nothing to scroll. Measuring the keyboard ourselves and padding
 * the scroll content works on both platforms, in Expo Go and in a build.
 */
export function useKeyboardHeight(): number {
  const [height, setHeight] = useState(0);

  useEffect(() => {
    // `Will*` fires before the animation on iOS; Android only has `Did*`.
    const showEvent = Platform.OS === 'ios' ? 'keyboardWillShow' : 'keyboardDidShow';
    const hideEvent = Platform.OS === 'ios' ? 'keyboardWillHide' : 'keyboardDidHide';

    const show = Keyboard.addListener(showEvent, (e) => setHeight(e.endCoordinates?.height ?? 0));
    const hide = Keyboard.addListener(hideEvent, () => setHeight(0));

    return () => {
      show.remove();
      hide.remove();
    };
  }, []);

  return height;
}
