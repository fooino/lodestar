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
- Message keys use `msg.*`. Never put user-facing English in the exception.
- `with()` must be free of secrets, tokens, passwords, and full request bodies.
- Wrap external exceptions with `cause()` or `from()`; never lose the original.
- Do not call `Log::error()` inside an exception; let Laravel's handler report it.

## Adding a code or an exception

Every exception extends `Fooino\Core\Exceptions\FooinoException`. To add a `_CODE` method to an existing class, or to create a new exception class, use the `exception-architect` agent.

The agent owns the code range, the class and method shape, and the test. Never choose a code by hand.
