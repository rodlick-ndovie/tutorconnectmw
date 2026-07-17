// Validates and replaces req[part] with the parsed result.
export const validate = (schema, part = 'body') => (req, res, next) => {
  try {
    req[part] = schema.parse(req[part]);
    next();
  } catch (err) {
    next(err);
  }
};
