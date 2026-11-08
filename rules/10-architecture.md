# 10 — Architecture

## Shape

```text
Application (composition root)
        │
        ├───────────────┬───────────────┐
        ▼               ▼               ▼
   fooino/media   fooino/setting   fooino/<domain>...
        │               │               │
        └───────────────┴───────────────┘
                        │
                        ▼
                   fooino/base
                        │
                        ▼
                   fooino/core

Arrows point to the dependency. Domain packages depend on base, never on
each other. The application is the only composition root.

Cross-package calls go through a base contract, not through the sibling:

   fooino/setting ──type-hints──► Fooino\Base\Contracts\Media
                                          ▲
                                          │ implements
                                   fooino/media

fooino/skeleton ──(copied to create a package)──► fooino/<domain>
                    starter kit; never installed at runtime
```

`base` and `core` are the floor: `base` is the shared foundation and contract hub, `core` is the generic layer beneath it. Nothing in the floor depends upward.

## Dependency rules

1. `core` depends on no Fooino package.
2. `base` requires `core`.
3. Domain packages require `base`; they never require another domain package.
4. The application requires the domain packages it uses and binds the contracts.
5. No package depends on the application.
6. Declare every Fooino package you import directly; do not rely on a transitive requirement.

## Layer contracts

- **core** provides math, date, JSON, sanitization, tokens, exceptions, and generic helpers. No database, Eloquent, models, migrations, HTTP request/response, routing, middleware, auth, or session. No Fooino config or contracts. If a helper needs those, it belongs in `base`.
- **base** provides models, requests, responses, middleware, tracking, casts, actions, jobs, Artisan commands, and contracts. It may use the database and HTTP. It never requires a domain package.

## Cross-package contracts

- Location: `fooino/base`, `src/Contracts/<Domain>.php`. The interface is named after the domain: `Media`, `Setting`.
- A contract is an interface only: no implementation, model, migration, or provider wiring.
- Package B implements the contract. Package A type-hints it and imports no class from B.
- The application binds the contract (`Media::class` → `MediaHandler::class`). `base` never binds a domain implementation.
- Consumer tests fake the contract. The implementation package is never a test dependency.
- A contract change is breaking for every implementer and consumer; it requires a base major bump.
- If the implementation is optional, make that explicit (`isAvailable()`, a nullable dependency, or `app()->bound()`).

## Ownership

### The package provides

Tasks (`run()`), Actions (`run()`), Artisan commands (`handle()`), models, requests, middleware, casts, concerns, enums, contracts, support classes, and provider registration for its own config, migrations, and commands.

### The application owns

Routes, controllers, response rendering, gates, policies, middleware aliases and attachment, scheduling, listeners, contract bindings, and the call sites that invoke Tasks, Actions, and Commands.

### Forbidden in every package

Routes, controllers, gates, policies, route registration, boot-time Task or Action invocation, assumptions about the invocation point, domain-specific config defaults, and `auth()` / `session()` / `request()` at construction time.

## Directories

| Directory | Contents | Naming |
|---|---|---|
| `src/Interfaces/` | Core contracts | `*able` |
| `src/Contracts/` | Cross-package contracts (base) | Domain noun |
| `src/Concretes/<Domain>/` | Handlers and managers | `Fooino*Handler`, `*Manager` |
| `src/Facades/` | Facades | Domain noun |
| `src/Exceptions/` | Fluent exceptions | `*Exception` |
| `src/Providers/` | Service providers | `*ServiceProvider` |
| `src/Support/` | Utilities | Noun |
| `src/Concerns/` | Traits | Trait |
| `src/Tasks/<Domain>/` | Work with `run()` | `*Task` |
| `src/Actions/` | One use case with `run()` | `*Action` |
| `src/Commands/` | Artisan commands | `*Command` |
| `src/Jobs/` | Queued jobs | Verb phrase |
| `src/Models/` | Eloquent models | Singular noun |
| `src/Http/{Middleware,Requests}/` | HTTP units | `*Middleware`, `*Request` |
| `src/Casts/` | Custom casts | `*Cast` |
| `config/`, `database/migrations/` | Config and schema | `fooino-<package>.php`, timestamped |
| `tests/Unit/`, `tests/Data/`, `tests/Fakes/` | Tests, datasets, fakes | `*UnitTest.php`, `Datasets.php`, `Fake*` |
| `docs/markdown/` | Documentation | `SCREAMING_SNAKE.md` |

If content does not fit a directory, propose a new one in the standards repo first.

## Migrations

- One migration per table; never edit a released migration.
- No foreign keys: no `foreign()`, `constrained()`, or cascade/restrict actions. The application adds them.
- Indexing is allowed: use the normal column type (`unsignedBigInteger`, `ulid`) with `->index()` or `$table->index(...)`.
- `down()` drops only what the package created: its table, columns, and indexes.

## Core manager pattern

`*able` interface → `*Handler` → `*Manager` (registered as a singleton) → facade. The facade `@method` list mirrors the interface.

## Units and transport

- A Task has one `run()` and returns a value.
- An Action is one use case; it may open a transaction and dispatch events.
- A Command validates input, calls one Task, and returns `self::SUCCESS` or `self::FAILURE`.
- A Job is transport only. Middleware holds no business logic.
