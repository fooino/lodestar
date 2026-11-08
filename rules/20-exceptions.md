# 20 — Exceptions

All package exceptions extend `Fooino\Core\Exceptions\FooinoException`, a fluent builder:

```php
app(FooinoRuntimeException::class)
    ->_3()
    ->with(['input' => $input])
    ->throw();
```

## Rules

- Never use `new SomeException(...)` or `throw new ...` in `src/`. Resolve from the container.
- `throw()` is terminal (`never`); never wrap it in `return`.
- Every error code is unique across the ecosystem. Never reuse or renumber a released code.
- Error methods are `final public function _<code>(): static` and set the message key, code, level, HTTP status, and reportability.
- Message keys use `msg.*`. Never put user-facing English in the exception.
- `with()` must be free of secrets, tokens, passwords, and full request bodies.
- Wrap external exceptions with `cause()` or `from()`; never lose the original.
- Do not call `Log::error()` inside an exception; let Laravel's handler report it.

## Code ranges

Ranges belong to the **exception class**, not to the package. One exception file owns one range.

| Range | Exception class | Domain | Size |
|---|---|---|---|
| 1–249 | `FooinoRuntimeException` | general runtime | 250 |
| 250–499 | `InfiniteLoopException` | infinite loop / recursion | 250 |
| 1000–1099 | `CanNotConvertDateException` | date conversion | 100 |
| 1100–1199 | `MathCalculationException` | math | 100 |
| 1200–1299 | `TokenGeneratorException` | token generation | 100 |
| 1300–1399 | free | next exception file | 100 |
| 1400–1499 | free | next exception file | 100 |
| 1500+ | free | next exception files | 100 each |

- `1–249` and `250–499` are 250-code ranges because those classes are extended often. New codes go inside the existing class, not a new file.
- `500–999` is reserved for future core exception classes. Do not use it for a package exception without a standards change.
- Every other exception **file** owns a full 100-code block. Two files never share a range.
- New exception files take the next free block at or above 1300.

## Adding a code

### Existing exception class

1. Find the highest used code in that class.
2. Add the next `final` method inside the class's range.
3. Update `docs/markdown/EXCEPTIONS.md` and add a test.

### New exception file

1. List every class extending `FooinoException` in core and the installed packages, then read the table above.
2. Take the next free 100-code block at or above 1300. Do not split or share a block.
3. Assign the block to the new class; its first code is the block start.
4. Add the class and range to the table above and to `docs/markdown/EXCEPTIONS.md`.
5. Add the method, docs, and test.
