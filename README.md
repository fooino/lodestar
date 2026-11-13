# Fooino Lodestar

The single source of truth for how Fooino Laravel packages and the Laravel application are written, tested, and reviewed — and for the instructions every AI agent follows in them.

- This repository owns the rules and the project deltas.
- `AGENTS.md` and every tool adapter are generated locally from it.
- Nothing generated is committed; rerunning the sync rebuilds it.

## How it works

Run this from a package or application root:

```bash
composer ai:sync
```

The command:

1. reads `composer.json` to get the project name,
2. uses `targets/<key>/delta.md` when that file exists,
3. writes `AGENTS.md` with the rule links and the project delta, if any,
4. writes the tool adapters selected by `--tool` (all of them by default).

An application is detected from the PSR-4 mapping to `app/` and takes its namespace from that mapping; a package derives its namespace from `composer.json`. A delta is optional.

## Layout

```text
rules/       Canonical rules, read in numeric order
agents/      Kilo subagents
commands/    Kilo commands
templates/   AGENTS.md and class templates
targets/     Project-specific AGENTS.md delta, one directory per project key
src/         Sync generator: action, console command, tasks, support
bin/         CLI entry point
docs/        Reference notes, not loaded into AGENTS.md
tests/       Pest tests and temp-directory helper
```

## Install

In a package or the application:

```bash
composer require --dev fooino/lodestar:^1.0
```

Add one script to `composer.json`:

```json
{
    "scripts": {
        "ai:sync": "@php vendor/fooino/lodestar/bin/sync.php"
    }
}
```

`AGENTS.md` is always written. Every tool adapter is written too unless `--tool` narrows it:

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

Codex reads `AGENTS.md` directly and needs no adapter.

## Kilo commands

The Kilo adapter mirrors `commands/` into `.kilo/commands/` and `agents/` into `.kilo/agents/`:

| Command | Purpose |
|---|---|
| `check-standards` | Audit the code against the rules; asks every file or branch diff first, then writes `RULES_AUDIT.md` |
| `file-tree` | Write every project file as a tree to `file-tree.md`, with `helpers.php` functions by name |
| `pre-pr` | Run the quality gate and fix formatting only |
| `new-error` | Add an error code through the `exception-architect` agent |
| `new-package` | Scaffold a copied skeleton into a new package |
| `release` | Prepare a package release |
| `sync-standards` | Regenerate `AGENTS.md` and the adapters |

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
/RULES_AUDIT.md
/file-tree.md
```

## Tests

```bash
composer test
composer test:coverage
```

Coverage must stay above 90%; `test:coverage` fails below that threshold.

## Adding a project

1. Add `targets/<key>/delta.md` when the project needs rules of its own. `<key>` is the part after `fooino/` in `composer.json`. Without it, the generated `AGENTS.md` carries the shared rules only.
2. From the project root, run `composer ai:sync`.
3. Add the `.gitignore` entries above and check the generated `AGENTS.md`.


## Changing a rule

1. Add or edit a file under `rules/`. It is picked up automatically; no list to update.
2. Run `composer ai:sync` from the project roots that need it. Generated files are ignored, so there is nothing to commit.

Project-specific rules go in `targets/<key>/delta.md`, never in `rules/`.

## License

Private and proprietary. See [LICENSE.md](LICENSE.md).
