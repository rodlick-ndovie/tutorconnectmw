// Normalise Malawi numbers stored in various formats (e.g. "0996...", "881...",
// "+265...") into an international form for tel:/wa.me links.
export function toIntlMw(raw?: string | null): string | null {
  if (!raw) return null;
  let n = raw.replace(/[^\d+]/g, '');
  if (n.startsWith('+')) n = n.slice(1);
  if (n.startsWith('265')) return n;
  if (n.startsWith('0')) return `265${n.slice(1)}`;
  if (n.length === 9) return `265${n}`; // bare 9-digit local number
  return n;
}
