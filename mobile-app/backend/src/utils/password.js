import argon2 from 'argon2';

// The PHP site stores argon2id hashes ($argon2id$...). The `argon2` package
// verifies those directly, and we create new hashes in the same format so the
// website and the API stay fully interchangeable.

export async function verifyPassword(hash, plain) {
  if (!hash || !plain) return false;
  try {
    return await argon2.verify(hash, plain);
  } catch {
    return false;
  }
}

export async function hashPassword(plain) {
  // Match PHP's password_hash(PASSWORD_ARGON2ID) defaults: m=65536, t=4, p=1.
  return argon2.hash(plain, {
    type: argon2.argon2id,
    memoryCost: 65536,
    timeCost: 4,
    parallelism: 1,
  });
}
