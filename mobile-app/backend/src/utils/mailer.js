import nodemailer from 'nodemailer';

// Best-effort SMTP mailer. If SMTP isn't configured, calls are no-ops that
// resolve to { sent: false } so request flows still succeed in dev.
const host = process.env.SMTP_HOST;
const configured = Boolean(host);

let transport = null;
if (configured) {
  transport = nodemailer.createTransport({
    host,
    port: Number(process.env.SMTP_PORT || 587),
    secure: Number(process.env.SMTP_PORT) === 465,
    auth: process.env.SMTP_USER
      ? { user: process.env.SMTP_USER, pass: process.env.SMTP_PASSWORD }
      : undefined,
  });
}

export const mailerConfigured = () => configured;

export async function sendMail({ to, subject, html, replyTo }) {
  if (!transport) {
    console.warn('[mailer] SMTP not configured; skipping email to', to);
    return { sent: false };
  }
  try {
    await transport.sendMail({
      from: process.env.MAIL_FROM || 'TutorConnect <no-reply@tutorconnectmw.com>',
      to,
      subject,
      html,
      replyTo,
    });
    return { sent: true };
  } catch (err) {
    console.error('[mailer] send failed:', err.message);
    return { sent: false };
  }
}
