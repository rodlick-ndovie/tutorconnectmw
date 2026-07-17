// Standard response envelope + helpers shared across modules.

export function ok(res, data, meta) {
  const body = { success: true, data };
  if (meta) body.meta = meta;
  return res.json(body);
}

export function created(res, data) {
  return res.status(201).json({ success: true, data });
}

export class ApiError extends Error {
  constructor(status, code, message) {
    super(message);
    this.status = status;
    this.code = code;
  }
}

export const errors = {
  badRequest: (msg = 'Bad request') => new ApiError(400, 'BAD_REQUEST', msg),
  unauthorized: (msg = 'Unauthorized') => new ApiError(401, 'UNAUTHORIZED', msg),
  forbidden: (msg = 'Forbidden') => new ApiError(403, 'FORBIDDEN', msg),
  notFound: (msg = 'Not found') => new ApiError(404, 'NOT_FOUND', msg),
  conflict: (msg = 'Conflict') => new ApiError(409, 'CONFLICT', msg),
};

/** Wrap an async route handler so thrown errors reach the error middleware. */
export const asyncHandler = (fn) => (req, res, next) =>
  Promise.resolve(fn(req, res, next)).catch(next);
