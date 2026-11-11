# 00 — Principles

These apply to every Fooino package. A package `AGENTS.md` may add constraints; it may not relax these without a standards change.

## Stack

- PHP `^8.5`, Laravel `^13`, MySQL `^9.7` — the versions every package targets.
- The application uses Redis for caching.
- Tests default to SQLite with the `array` cache driver. A test that needs real MySQL or Redis guards itself, as described in `30-testing.md`.

## Language and types

- Declare every parameter and return type. Use union types when values genuinely vary.
- `mixed` only when the value is truly arbitrary.
- Use named arguments on every call, including PHP, Laravel, and vendor functions. Only variadic arguments stay positional.
- Fluent configuration methods return `static`.
- Do not add `declare(strict_types=1)` to an existing file without a separate decision.

## Naming

- Methods, variables, and properties: `camelCase`. Constants: `SCREAMING_SNAKE_CASE`.
- Booleans read as questions: `isJson()`, `hasTarget()`, `canRun()`.
- Getters and setters: `getPrecision()` / `setPrecision()`.
- Core interfaces: `*able` (`Jsonable`). Cross-package contracts: the domain noun (`Media`, `Setting`).
- Suffixes: `*Task`, `*Action`, `*Command`, `*Middleware`, `*Request`, `*Event`.
- Models are singular; tables are plural.
- Names are intention-revealing, pronounceable, and searchable: `remainingAttempts` over `n`, `expiresAt` over `date`. If a name needs a comment to be understood, rename it.
- Methods are verb phrases — `saveInvoice()`, `deletePage()`, `run()` — and variables are nouns: `$invoice`, `$remainingAttempts`.

## phpDoc

- Every method has a one-line description explaining **why** or **what**, not how.
- Use the multi-line block form, even for one line.
- No `@param` or `@return`; the signature is the source of truth. `@throws` is allowed.
- Model relationships, scopes, accessors, and mutators are exempt.

## Style

- Pint is the only formatter. Run `./vendor/bin/pint`, verify with `./vendor/bin/pint --test`.
- One space around operators; `$a . $b`; `!$value`; `fn() => ...`; no trailing commas; keep `string|null` as written.
- Never hand-format or add suppression comments. If a rule is wrong, change `pint.json` and its rationale in `pint.md`.
- No `dd`, `dump`, `var_dump`, `ray`, `print_r`, or `error_log` in committed code.
- `pint.json` is shared across packages; change it everywhere in one change.

## Quality gates

- Every new or changed line has a test.
- Line coverage at least **95%**; type coverage at least **90%**.
- After every task, run `./vendor/bin/pest --parallel --tia`; fall back to `./vendor/bin/pest` when the failure detail is not enough.
- `./vendor/bin/pint --test` passes before finishing.

## Security defaults

- `env()` only inside `config/`; never commit secrets.
- Validate and normalize input; escape output.
- Bind every SQL value; never interpolate.
- No secrets, tokens, passwords, or PII in logs or exception context.

## Change hygiene

- One logical change per commit or pull request; no drive-by reformatting.
- Update `CHANGELOG.md` for user-visible changes.
- A package `AGENTS.md` is generated; package notes go in `targets/<package>/delta.md`.
- Never edit generated `AGENTS.md` or local tool adapters by hand; change the rules and re-sync.
