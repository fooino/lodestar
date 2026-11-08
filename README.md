# Fooino Lodestar

The single source of truth for how Fooino Laravel packages are written, tested, and reviewed — and for the instructions every AI agent follows inside them.

- This repository owns the rules and package-specific deltas.
- `AGENTS.md` and every tool adapter are generated locally from it.
- Nothing generated is committed; rerunning the sync rebuilds it.

## How it works

Run this from a package root:

```bash
composer ai:sync
```

The command:

1. reads the package `composer.json` to get its name,
2. finds `targets/<key>/delta.md` in this repository,
3. writes `AGENTS.md` with the shared rules and the package delta,
4. writes the tool adapters selected by `--tool` (all of them by default).

## Layout

```text
rules/       Canonical rules, read in numeric order
agents/      Kilo subagents
commands/    Kilo commands
templates/   AGENTS.md and class templates
targets/     Package-specific AGENTS.md delta, one directory per package key
src/         Sync generator: action, console command, tasks, support
bin/         CLI entry point
docs/        Reference notes, not loaded into AGENTS.md
tests/       Pest tests and temp-directory helper
```

## Install in a package

```bash
composer require --dev fooino/lodestar:^1.0
```

## Commands

Add one script to each package's `composer.json`:

```json
{
    "scripts": {
        "ai:sync": "@php vendor/fooino/lodestar/bin/sync.php"
    }
}
```

`AGENTS.md` is always written. Every tool adapter is written too unless `--tool` narrows it to one:

```bash
composer ai:sync                  # AGENTS.md + all adapters
composer ai:sync -- --tool=kilo   # AGENTS.md + the kilo adapter
```

| `--tool` | Adapter written |
|---|---|
| `all` (default) | every adapter listed below |
| `kilo` | `kilo.jsonc` + `.kilo/**` |
| `cursor` | `.cursor/rules/fooino-standards.mdc` |
| `claude` | `CLAUDE.md` |

`Codex` reads `AGENTS.md` directly and needs no adapter.

## `.gitignore`

Every generated file is rebuildable local output; rerun `composer ai:sync` to refresh it. We suggest ignoring them so generated output stays out of history and never gets edited by hand:

```gitignore
/.kilo
/kilo.jsonc
/.cursor
/.mcp.json
/boost.json
/CLAUDE.md
/AGENTS.md
```

## Tests

```bash
composer test
composer test:coverage
```

Coverage must stay above 90%; `test:coverage` fails below that threshold.

## Adding a package

1. Add `targets/<key>/delta.md` to this repository. `<key>` is the package name after `fooino/` in `composer.json`.
2. From the new package root, run `composer ai:sync`.
3. Add the `.gitignore` entries above and check the generated `AGENTS.md`.

See [commands/new-package.md](commands/new-package.md) for the full checklist.

## Changing a rule

1. Add or edit a file under `rules/`. It is picked up automatically; no list to update.
2. Run `composer ai:sync` from the package roots that need it. Generated files are ignored, so there is nothing to commit.

Package-specific rules go in `targets/<key>/delta.md`, never in `rules/`.

## License

Private and proprietary. See [LICENSE.md](LICENSE.md).
