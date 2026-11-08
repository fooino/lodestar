# Fooino Lodestar

The single source of truth for how Fooino Laravel packages are written, tested, and reviewed — and for the instructions every AI agent follows inside them.

- This repository owns the rules and package-specific deltas.
- Each package commits only `AGENTS.md`.
- Tool adapters are generated locally and gitignored.

## How it works

Run this from a package root:

```bash
composer ai:sync
```

The command:

1. reads the package `composer.json` to get its name,
2. finds `targets/<key>/delta.md` in this repository,
3. writes `AGENTS.md` with the shared rules and the package delta,
4. writes all three tool adapters by default.

There is no lock file and no drift check. The command only makes the agent aware of the standards.

## Layout

```text
rules/                 Canonical rules, read in order
  00-principles.md     Types, naming, phpDoc, style, boundaries
  10-architecture.md   Layers, contracts, ownership, directories
  20-exceptions.md     Exception ranges and fluent codes
  30-testing.md        Pest structure, datasets, contract fakes
  40-eloquent.md       Models, scopes, migrations, queries
agents/                architecture-guard, contract-reviewer, test-engineer
commands/              pre-pr, new-error, new-package, release, sync-standards
templates/AGENTS.md
templates/Exception.php.tpl
templates/Model.php.tpl
targets/<key>/delta.md Package-specific AGENTS.md section
src/Actions/SyncStandardsAction.php  Orchestrates the sync
src/Console/SyncStandardsCommand.php Symfony Console command
src/Enums/Tool.php     Backed enum mapping tools to tasks
src/Support/Filesystem.php  Guarded read, write, copy, delete
src/Tasks/             GetCurrentPackageTask, GetRuleFilesTask,
                       GetAgentsContentTask, WriteAgentsFileTask,
                       WriteKiloFilesTask, WriteCursorFileTask,
                       WriteClaudeFileTask
bin/sync.php           CLI entry point
docs/pint.md           Pint rationale (reference, not loaded)
tests/                 Pest tests and temp-directory helper
```

## Install in a package

```bash
composer require --dev fooino/lodestar:^1.0
```

## Commands

Add these to each package's `composer.json`:

```json
{
    "scripts": {
        "ai:sync": "@php vendor/fooino/lodestar/bin/sync.php",
        "ai:kilo": "@php vendor/fooino/lodestar/bin/sync.php --tool=kilo",
        "ai:cursor": "@php vendor/fooino/lodestar/bin/sync.php --tool=cursor",
        "ai:claude": "@php vendor/fooino/lodestar/bin/sync.php --tool=claude"
    }
}
```

| Command | Effect | Committed? |
|---|---|---|
| `composer ai:sync` | Write `AGENTS.md` + all adapters (default) | `AGENTS.md` only |
| `composer ai:kilo` | Write `kilo.jsonc` + `.kilo/**` | No |
| `composer ai:cursor` | Write `.cursor/rules/fooino-standards.mdc` | No |
| `composer ai:claude` | Write `CLAUDE.md` | No |

`composer ai:sync` defaults to all three adapters. Use `composer ai:kilo`, `ai:cursor`, or `ai:claude` for one adapter only.

Codex reads `AGENTS.md` directly and needs no adapter.

## Tests

```bash
composer test
composer test:coverage
```

Coverage must stay above 90%; `test:coverage` fails below that threshold.

Add these to the package `.gitignore`:

```gitignore
/.kilo
/kilo.jsonc
/.cursor
/CLAUDE.md
/.mcp.json
/boost.json
```

## Adding a package

1. Add `targets/<key>/delta.md` to this repository. `<key>` is the package name after `fooino/` in `composer.json`.
2. From the new package root, run `composer ai:sync`.
3. Commit `AGENTS.md`.

See [commands/new-package.md](commands/new-package.md) for the full checklist.

## Changing a rule

1. Add or edit a file under `rules/`. It is picked up automatically; no list to update.
2. Run `composer ai:sync` from the package roots that need it.
3. Commit the regenerated `AGENTS.md` files.

Package-specific rules go in `targets/<key>/delta.md`, never in `rules/`.

## License

Private and proprietary. See [LICENSE.md](LICENSE.md).
