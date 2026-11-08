## Package delta — skeleton

`fooino/skeleton` is a starter kit, never a runtime dependency. A generated package copies it, replaces the namespace and provider, and then requires `fooino/base` (which brings `fooino/core`).

### Architecture

| Directory | Contents | Naming |
|---|---|---|
| `src/Providers/` | `SkeletonServiceProvider` | `*ServiceProvider` |
| `src/` | Only what the scaffold needs | — |
| `config/` | Added when the package needs config | `fooino-<package>.php` |
| `tests/Unit/`, `docs/markdown/` | Tests and docs | `*UnitTest.php`, `SCREAMING_SNAKE.md` |

### Rules

- Keep `boot()` and `register()` composed of small, named protected methods.
- Do not add domain code to skeleton. Copy it and add `targets/<key>/delta.md` to the standards repository.
- A generated package requires `fooino/base`, not `fooino/skeleton`.
- If it needs another domain's capability, add a contract to `fooino/base` first.
- If the package adds an exception file, claim its own 100-code range in `rules/20-exceptions.md`; there is no package-wide block.

### Exceptions and tests

Skeleton normally has no exception classes. Each exception **class** owns its own 100-code range. After every task, run `./vendor/bin/pest --parallel --tia`; fall back to `./vendor/bin/pest` when the failure detail is not enough.
