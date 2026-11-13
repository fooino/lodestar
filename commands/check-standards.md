---
description: Audit code against the Fooino standards, report violations and uncovered areas to RULES_AUDIT.md.
---

Run this from the project root. This is a reading audit: do not edit source, tests, or rules. The only file you may write is `RULES_AUDIT.md`.

## Ask first

Ask: **"Check every file, or only the files changed in the current branch?"** Wait for the answer. Do not read code before it.

- **Changed** — the usual choice for a large project. The branch diff against the base branch, plus staged, unstaged, and untracked files.
- **Every file** — all tracked code, still excluding the paths under *Skip*.

If the answer is ambiguous, ask again; do not choose for the developer.

## Select files

Resolve the base branch: `origin/HEAD` when it exists, otherwise `main`, `master`, then `develop`. If none resolves, ask which base branch to compare against.

```bash
base=$(git symbolic-ref refs/remotes/origin/HEAD 2>/dev/null | sed 's|refs/remotes/origin/||')
base=${base:-main}
git diff --name-only "$base"...HEAD
git diff --name-only
git diff --cached --name-only
git ls-files --others --exclude-standard
```

For **Every file**, use `git ls-files` instead of the diff commands. Keep `.php`, `composer.json`, `phpunit.xml`, `pint.json`, and files under `config/` and `database/migrations/`; ignore `.md`. Never widen the scope on your own.

**Skip:** `vendor/`, `node_modules/`, `storage/`, `bootstrap/cache/`, `public/build/`, `coverage/`, `.phpunit.cache/`, `.kilo/`, `.cursor/`, `kilo.jsonc`, `CLAUDE.md`, `AGENTS.md`.

If the selection is empty, say so and stop.

## Read the standards

Read in order: `00-principles.md`, `10-architecture.md`, `20-exceptions.md`, `30-testing.md`, `40-eloquent.md`. Apply only what a rule file states; never invent a rule.

Packages use `src/`; the application uses `app/`, `routes/`, `config/`, `database/`, and `tests/`. Read `composer.json` to tell them apart — a PSR-4 mapping to `app/` means application.

## Check

For each selected file:

- **00** — declared types, named arguments, phpDoc block shape, naming, `mixed`, style, security defaults.
- **10** — file placement and layer, dependency direction, `handle()` logic in Commands and Jobs, Job payloads, constructor vs `app()` resolution, routes/controllers/gates ownership, foreign keys in package migrations.
- **20** — no `new` or `throw new`, fluent codes, `msg.*` keys, safe `with()`, `cause()`/`from()` wrapping.
- **30** — test file naming and placement, `describe`/`it` grouping, no test per tiny assertion, `handle exceptions` describe, datasets, Pest vs PHPUnit assertions, database and service guards.
- **40** — model banners, `#[Guarded('id')]`, casts, scopes, migrations, queries, `Batch`.

For every finding record `file:line`, the rule that owns it, what is wrong, and the minimal fix. Continue after the first finding; report all of them.

## Uncovered areas

List separately what the standards do not cover yet. A finding belongs here when the change introduces a pattern, directory, dependency, framework feature, or test shape that no rule file addresses — for example a new `app/Services/` layer, a repository pattern, observers, broadcasting, a new Job payload type, or a test type the rules never mention.

For each: the evidence, the rule file that should own it (`00`, `10`, `20`, `30`, or `40`), and one proposed rule sentence. Do not add the rule yourself.

## Report

Write `RULES_AUDIT.md` at the project root, overwriting the previous run. Tell the developer to add `/RULES_AUDIT.md` to `.gitignore`; never commit it.

```markdown
# Rules audit — <project>

- **Scope:** changed since `<base>` (N files) | every file (N files)
- **Date:** <YYYY-MM-DD>
- **Verdict:** PASS | N violations, M gaps

## Violations

| # | File:line | Rule | Problem | Minimal fix |
|---|---|---|---|---|

## Uncovered areas

| Area | Evidence | Rule file | Proposed rule |
|---|---|---|---|

## Not reviewed

- `<path>` — generated, excluded, binary, or unreadable
```

If two rules contradict each other for a finding, report both under Violations instead of choosing one. If nothing is found, write the report with `PASS` and leave both tables empty.
