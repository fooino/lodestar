# Fooino Lodestar

You are working on the standards repository itself, not on a consuming Fooino package.

This file is hand-written and applies only here. It is never generated and never ships: the file a package receives is produced from `templates/AGENTS.md`.

Read `rules/00-principles.md` and `rules/10-architecture.md` before changing the generator, and the owning file before editing a rule.

## Layout

- `rules/` — shared rules, loaded in numeric order. The product.
- `agents/` — Kilo subagents. YAML frontmatter, then the prompt.
- `commands/` — Kilo commands. YAML frontmatter, then the procedure.
- `templates/` — AGENTS.md and class templates sent to packages.
- `targets/` — one `delta.md` per package key; the only package-specific rules.
- `src/` — the sync generator: actions, console, enums, tasks, support.
- `bin/` — CLI entry point.
- `docs/` — reference material, not loaded into `AGENTS.md`.
- `tests/` — Pest tests for the generator.

`rules/`, `agents/`, `commands/`, and `templates/` are shipped content; `src/` and `bin/` only produce it. A rule edit reaches every package's `AGENTS.md` on its next sync, so treat it as a standards change, not a refactor.

## Rules for changing this repository

- Rules are written for **all** Fooino packages. Package-specific notes go in `targets/<key>/delta.md`.
- Adding a rule is adding `rules/<NN>-<name>.md`. The generator discovers it; there is no list to update.
- Every rule must be actionable. "Write clean code" is not a rule; "new tests use `it()` inside `describe()`" is.
- Do not restate a rule another file owns; link to it.
- All file access goes through `Filesystem` in `src/Support`; no other class reads, writes, copies, or deletes files.
- One task per responsibility in `src/Tasks`; the action orchestrates, the command reports.
- The CLI is public: when a script name, flag, or output path changes, update `README.md` and `commands/sync-standards.md` in the same change.
- Record user-visible changes in `CHANGELOG.md`.
- Never commit or hand-edit generated files in a package (`AGENTS.md` and the adapters); change the rule, template, or target delta, then re-sync.

## Tests

```bash
composer test
composer test:coverage   # fails below 90%
```

This repository runs at 90%; the rules it ships ask 95% of packages. Cover new scenarios and their failure paths, including an unknown `--tool` value.

Then verify generation end to end from a package wired to this working copy through a path repository (`0-skeleton` and `1-core` in this workspace; a VCS install only sees the published copy):

```bash
cd ../0-skeleton && composer ai:sync -- --tool=kilo
```

Read the generated `AGENTS.md` and adapter. Tests cover the generator; only a real sync proves the output.

## Agent behavior

- When asked to change a rule, name the file that owns the concern. If several could, pick one and cross-reference.
- When asked to add a package, follow `commands/new-package.md` exactly.
- When asked why a generated `AGENTS.md` is wrong, fix the rule, template, or target delta, then re-sync that package.
- When asked to add a tool adapter, extend `Tool` and its task, then document the new `--tool` value in `README.md` in the same change.
