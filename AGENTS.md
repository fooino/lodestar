# Fooino Lodestar

You are working on the standards repository, not on a consuming package.

Read `rules/00-principles.md` and `rules/10-architecture.md` before changing anything.

## Rules for changing this repository

- Rules are written for **all** Fooino packages. Put package-specific notes in `targets/<package>/delta.md`.
- Adding a rule is just adding `rules/<NN>-<name>.md`. The generator discovers it automatically; there is no list to update.
- Every rule must be actionable. "Write clean code" is not a rule; "new tests use `it()` inside `describe()`" is.
- Do not restate the same rule in two files. Link to the owning file instead.
- After any change, run this from a package root: `composer ai:sync`.
- Never edit a package's generated `AGENTS.md` by hand.

## Layout

- `rules/` — shared rules, loaded in numeric order.
- `agents/` — Kilo subagents. YAML frontmatter, then the prompt.
- `commands/` — Kilo commands. YAML frontmatter, then the procedure.
- `templates/AGENTS.md` — the generated AGENTS.md template.
- `templates/Exception.php.tpl` — the new exception class template.
- `templates/Model.php.tpl` — the new Eloquent model template.
- `targets/<package>/delta.md` — package-specific section of `AGENTS.md`.
- `src/Actions/SyncStandardsAction.php` — orchestrates AGENTS.md and adapter generation.
- `src/Console/SyncStandardsCommand.php` — Symfony Console entry point.
- `src/Enums/Tool.php` — tools and the task each one runs.
- `src/Support/Filesystem.php` — the only class that reads, writes, copies, or deletes files.
- `src/Tasks/` — one task per responsibility.
- `bin/sync.php` — CLI bootstrap.

## Tests

Run `composer test` and `composer test:coverage`; coverage must stay above 90%.

## Agent behavior

When asked to change a rule, identify which rule file owns the concern. If more than one could own it, pick one and cross-reference.

When asked to add a package, follow `commands/new-package.md` exactly.

When asked why `AGENTS.md` is wrong, fix the rule or `targets/<key>/delta.md`, then run `composer ai:sync` from that package root.
