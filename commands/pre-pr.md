---
description: Run the full Fooino quality gate, fix formatting only, and report failures that need a human.
---

Run the pre-PR gate for this package. Read `vendor/fooino/lodestar/rules/00-principles.md` and `vendor/fooino/lodestar/rules/30-testing.md` first.

## Steps

1. `./vendor/bin/pint` — format only. Do not make behavioral edits.
2. `./vendor/bin/pint --test` — must pass.
3. `./vendor/bin/pest --parallel --tia` — run after the task.
4. If the failure detail is not enough, TIA is stale, or config/composer/phpunit/bootstrap/migrations changed, run the full suite: `./vendor/bin/pest`.
5. Coverage: `./vendor/bin/pest --coverage --min=95`.
6. Type coverage: `./vendor/bin/pest --type-coverage --min=90`.

## Output

Return a checklist with the exact command and result. If anything fails:

- fix formatting failures with Pint;
- do not lower thresholds;
- do not edit tests to hide a real failure;
- report source failures with the failing test name and the likely cause.

Never commit, tag, or push.
