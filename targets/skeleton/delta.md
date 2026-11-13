## Package delta — skeleton

`fooino/skeleton` is a starter kit, never a runtime dependency: a new package copies it, renames the namespace and provider, and then requires `fooino/base`.

- Keep `boot()` and `register()` composed of small, named protected methods.
- Do not add domain code to skeleton; copy it into the new package.
