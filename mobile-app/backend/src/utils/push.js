// Minimal Expo push client. Sends to the Expo push service; failures are
// logged but never break the request that triggered the notification.
const EXPO_PUSH_URL = 'https://exp.host/--/api/v2/push/send';

export async function sendExpoPush(tokens, { title, body, data }) {
  const valid = (tokens || []).filter((t) => typeof t === 'string' && t.startsWith('ExponentPushToken'));
  if (valid.length === 0) return { sent: 0 };

  const messages = valid.map((to) => ({ to, title, body, data, sound: 'default' }));

  try {
    const res = await fetch(EXPO_PUSH_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify(messages),
    });
    if (!res.ok) {
      console.error('[push] expo responded', res.status);
      return { sent: 0 };
    }
    return { sent: valid.length };
  } catch (err) {
    console.error('[push] send failed:', err.message);
    return { sent: 0 };
  }
}
