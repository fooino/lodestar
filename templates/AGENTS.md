# {{title}}

<!-- GENERATED FILE — do not edit by hand. -->
<!-- Source: fooino/lodestar -->

**Before you plan, edit, or run anything, read the Fooino standards in this order:**

{{rules_links}}

Canonical location: `vendor/fooino/lodestar/rules/`. If it is missing, run `composer install` (or `composer ai:sync`), or read the same files from `../lodestar/rules/` or https://github.com/fooino/lodestar/tree/main/rules. Do not proceed without reading them.

**Package:** `{{composer}}` · **Namespace:** `{{namespace}}`

{{delta}}

## Critical rules (summary)

- Always use named arguments; declare every parameter and return type.
- Every method has a one-line, multi-line-block phpDoc. No `@param` or `@return`.
- Pint is the only formatter: `./vendor/bin/pint`, then `./vendor/bin/pint --test`.
- No `dd`, `dump`, `var_dump`, `ray`, `print_r`, or `error_log` in committed code.
- Exceptions are fluent: `app(Exception::class)->_CODE()->throw()`. Never `new` or `throw new`.
- `env()` only inside `config/`. Never commit secrets.
- Packages expose units; the application owns routes, controllers, gates, policies, and call sites.
- Cross-package calls use a contract in `fooino/base`; tests fake the contract.
- No foreign keys in package migrations; the application adds them.
- Coverage is 95% line and 90% type. Every changed line has a test.

The files listed at the top are authoritative; this summary is only a safety net.

## Definition of done

Run from the package root:

```bash
./vendor/bin/pint --test
./vendor/bin/pest --parallel --tia
./vendor/bin/pest --coverage --min=95
./vendor/bin/pest --type-coverage --min=90
```

Run TIA after every task; fall back to `./vendor/bin/pest` when the failure detail is not enough.

## Tool adapters

`AGENTS.md` and every adapter are generated locally from `fooino/lodestar` and gitignored. Re-sync to rebuild them; nothing generated is committed.

```bash
composer ai:sync                    # AGENTS.md + all adapters
composer ai:sync -- --tool=kilo     # kilo.jsonc + .kilo/{rules,agents,commands}
composer ai:sync -- --tool=cursor   # .cursor/rules/fooino-standards.mdc
composer ai:sync -- --tool=claude   # CLAUDE.md
```

Codex reads `AGENTS.md` directly and needs no adapter.

## Keeping standards in sync

Never edit this file by hand. Change `fooino/lodestar` and re-sync from the package root. This also refreshes the local tool adapters:

```bash
composer ai:sync
```
