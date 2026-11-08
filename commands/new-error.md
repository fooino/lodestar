---
description: Add a Fooino error code in the correct range, with docs and tests.
---

Read `vendor/fooino/lodestar/rules/20-exceptions.md` first.

## Steps

1. Read the code-range table in the rule and list every class that extends `FooinoException` in core and the installed packages.
2. Decide the case:
   - **Existing class:** use the next unused code inside that class's own range.
   - **New exception file:** take the next free 100-code block at or above 1300; the class's first code is the block start. Never share or split a block.
3. Start from `templates/Exception.php.tpl` and add a `final public function _<code>(): static` method that sets the message key, code, level, HTTP status, and reportability.
4. Add the class and range to the rule table and to `docs/markdown/EXCEPTIONS.md`.
5. Add a test asserting class, code, message key, level, status, and reportability.
6. Run the focused test and `./vendor/bin/pint`.

## Guardrails

- Never reuse or renumber a released code.
- Never assign one range to two exception files.
- Never put user-facing English in the exception; use a `msg.*` key.
- Never use `new` or `throw new`.
- Do not touch unrelated exception methods.
