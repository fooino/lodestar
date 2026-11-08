---
description: Regenerate AGENTS.md and optional tool adapters from the standards repository.
---

The standards repository is the source of truth. `AGENTS.md` and every tool adapter are generated locally and gitignored; a package commits none of them.

## Regenerate

Run from the package root:

```bash
composer ai:sync
```

The command reads the package `composer.json`, finds `targets/<key>/delta.md`, and writes `AGENTS.md` plus all tool adapters. Pass `--tool=kilo`, `--tool=cursor`, or `--tool=claude` to write only one.

## Tool adapters

`ai:sync` already writes all three. Managed `.kilo/{rules,agents,commands}` directories are cleared of their `.md` files first, so deleted rules disappear from packages.

Composer consumes its own options, so keep the `--` separator; without it `--tool` is ignored. For one adapter:

```bash
composer ai:sync -- --tool=kilo     # kilo.jsonc + .kilo/{rules,agents,commands}
composer ai:sync -- --tool=cursor   # .cursor/rules/fooino-standards.mdc
composer ai:sync -- --tool=claude   # CLAUDE.md
```

The binary can be called directly instead: `php vendor/fooino/lodestar/bin/sync.php --tool=kilo`.

Codex reads `AGENTS.md` directly and needs no adapter.

## From the standards repository

```bash
cd ../1-core
composer ai:sync
```

## After syncing

1. Review `AGENTS.md`.
2. Keep it gitignored; it is rebuilt by the next sync.
3. Put package-specific notes in `targets/<key>/delta.md` in the standards repository.

## Guardrails

- Never edit `AGENTS.md` in a package directly.
- Never commit generated files: `AGENTS.md` and every tool adapter are local output.
