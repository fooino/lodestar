## Package delta — core

`fooino/core` is the generic, portable foundation. It provides tools, not application features. A bug here cascades everywhere.

### Architecture

| Directory | Contents | Naming |
|---|---|---|
| `src/Interfaces/` | Core contracts | `*able` |
| `src/Concretes/<Domain>/` | Handlers and managers | `Fooino*Handler`, `*Manager` |
| `src/Facades/` | `Json`, `Date`, `Math` | Domain noun |
| `src/Exceptions/` | Fluent exceptions | `*Exception` |
| `src/Providers/` | `CoreServiceProvider` | `*ServiceProvider` |
| `src/Support/` | `Sanitizer`, `TokenGenerator`, `SingletonableTask` | Noun |
| `src/Tasks/` | Generic cached work | `*Task` |
| `src/Enums/` | Native enums | Singular noun |
| `src/Concerns/` | Generic traits only | Trait |
| `tests/Unit/`, `docs/markdown/` | Tests and docs | `*UnitTest.php`, `SCREAMING_SNAKE.md` |

### Domains

| Domain | Interface | Handler | Manager | Facade |
|---|---|---|---|---|
| JSON | `Jsonable` | `FooinoJsonHandler` | `JsonManager` | `Json` |
| Date | `Dateable` | `FooinoDateHandler` | `DateManager` | `Date` |
| Math | `Mathable` | `FooinoMathHandler` | `MathManager` | `Math` |

Interface → handler → manager (singleton) → facade. Facade `@method` mirrors the interface.

### Exception ranges

Each exception **class** owns its own range. New exception files claim the next free 100-code block at or above 1300.

| Range | Class | Domain |
|---|---|---|
| 1–249 | `FooinoRuntimeException` | general runtime |
| 250–499 | `InfiniteLoopException` | infinite loop / recursion |
| 1000–1099 | `CanNotConvertDateException` | date |
| 1100–1199 | `MathCalculationException` | math |
| 1200–1299 | `TokenGeneratorException` | token generation |

### Portability

- No database, Eloquent, models, or migrations.
- No HTTP requests, responses, routing, middleware, auth, or session.
- No Fooino config keys, domain contracts, or `fooino-base` dependency.
- If a helper or trait needs any of those, it belongs in `fooino/base`.

### Migration note

`ApiResourceable` and `NormalizesInputs` are moving to `base`, along with request-oriented helpers such as `jsonRespond`, `perPage`, `resolveRequest`, and `nullIfBlankInput`. Do not add new request-dependent code here.

### Tests

After every task, run `./vendor/bin/pest --parallel --tia`; fall back to `./vendor/bin/pest` when the failure detail is not enough or TIA is stale. Core tests must not need a database or an HTTP request.
