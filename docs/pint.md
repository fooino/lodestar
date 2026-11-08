# Pint rationale

Reference for `pint.json`. This file is not loaded as an agent rule; the always-on style rules live in `rules/00-principles.md`.

Base: the `laravel` preset.

| Rule | Configuration | Why |
|---|---|---|
| `binary_operator_spaces` | `default: single_space`, `operators: { "=>": "align_single_space_minimal" }` | One space around operators; multi-line `=>` arrows align minimally per consecutive block. A blank line ends a block; inline and single-element arrays keep one space. |
| `concat_space` | `spacing: one` | `$a . $b`, not `$a.$b`. |
| `fully_qualified_strict_types` | `false` | Keep fully qualified class names where written. |
| `function_declaration` | `closure_fn_spacing: none` | `fn() => ...`, not `fn () => ...`; regular closures keep `function ()`. |
| `new_with_parentheses` | `false` | Keep `new Foo` without parentheses when no arguments are passed. |
| `no_extra_blank_lines` | `false` | Keep deliberate blank lines inside blocks. |
| `not_operator_with_successor_space` | `false` | `!$value`, not `! $value`. |
| `nullable_type_declaration` | `false` | Keep `string|null` as written; do not rewrite to `?string`. |
| `ordered_imports` | `false` | Keep the manual `use` order and grouping. |
| `single_line_empty_body` | `true` | Empty bodies may stay on one line; bodies with comments are untouched. |
| `trailing_comma_in_multiline` | `false` | No trailing commas in arrays, calls, or parameters. |

## Commands

```bash
./vendor/bin/pint              # format
./vendor/bin/pint --test       # verify (CI gate)
./vendor/bin/pint --test -v    # show diffs
./vendor/bin/pint --dirty      # format only uncommitted files
./vendor/bin/pint --no-config  # raw Laravel preset, for comparison
```

When `--test` reports a fixer that fights the project style, change `pint.json` and update this file. Do not add suppression comments.
