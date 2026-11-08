---
description: Regenerate AGENTS.md and optional tool adapters from the standards repository.
---

The standards repository is the source of truth. A package commits only `AGENTS.md`.

## Regenerate

Run from the package root:

```bash
composer ai:sync
```

The command reads the package `composer.json`, finds `targets/<key>/delta.md`, and writes `AGENTS.md` plus all tool adapters. Pass `--tool=kilo`, `--tool=cursor`, or `--tool=claude` to write only one.

## Tool adapters

`ai:sync` already writes all three. Run one of these for a single adapter:

```bash
composer ai:kilo     # kilo.jsonc + .kilo/{rules,agents,commands}
composer ai:cursor   # .cursor/rules/fooino-standards.mdc
composer ai:claude   # CLAUDE.md
```

Codex reads `AGENTS.md` directly and needs no adapter.

## From the standards repository

```bash
cd ../1-core
composer ai:sync
```

## After syncing

1. Review `AGENTS.md`.
2. Commit it with the change that required it.
3. Put package-specific notes in `targets/<key>/delta.md` in the standards repository.

## Guardrails

- Never edit `AGENTS.md` in a package directly.
- Tool adapters are generated locally and must stay gitignored.
