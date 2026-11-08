## Package delta — base

`fooino/base` is the Fooino ecosystem application layer. It may use the database and HTTP; `core` must stay generic. The consuming application owns routes, controllers, gates, and policies.

### Architecture

| Directory | Contents | Naming |
|---|---|---|
| `src/Contracts/` | Cross-package contracts | Domain noun (`Media`) |
| `src/Actions/` | One use case | `*Action` |
| `src/Tasks/<Domain>/` | Work with `run()` | `*Task` |
| `src/Commands/` | Artisan commands | `*Command` |
| `src/Models/` | Eloquent models | Singular noun |
| `src/Http/Middleware/`, `src/Http/Requests/` | HTTP units | `*Middleware`, `*Request` |
| `src/Jobs/`, `src/Events/`, `src/Casts/` | Jobs, events, casts | `*Job`, `*Event`, `*Cast` |
| `src/Concerns/` | `Modelable`, `Filterable`, `Prioritizable`, `Loggable`, `HasUser`, `HasTarget`, `Enumable`, `ApiResourceable`, `NormalizesInputs` | Trait |
| `config/`, `database/migrations/` | Config and schema | `fooino-base.php`, timestamped |
| `tests/Unit/`, `tests/Fakes/`, `tests/Data/` | Tests, fakes, datasets | `*UnitTest.php`, `Fake*`, `Datasets.php` |
| `docs/markdown/` | Documentation | `SCREAMING_SNAKE.md` |

### Contracts

- `src/Contracts/<Domain>.php`, interface named after the domain: `Media`, `Setting`.
- Domain packages implement contracts; consumers type-hint them. No sibling package dependencies.
- The application binds `Media::class` to the implementation. `base` never binds a domain implementation.
- Consumer tests fake the contract; the implementer is never a test dependency.
- A contract change is breaking for every implementer and consumer.

### Ownership

- `base` exposes Tasks, Actions, Artisan commands, middleware, requests, models, and contracts.
- The application owns routes, controllers, gates, policies, middleware attachment, scheduling, and every call site.
- No routes, controllers, gates, or policies in `base`.
- No Task or Action invocation from `boot()` or `register()`.

### Models

Every model uses `Modelable` and four banner sections: Relationships; Accessors, Mutators & Casts; Scopes; Custom Functions. `RequestRecord` and `Trash` are the reference implementations.

### Migrations

- No foreign keys: no `foreign()`, `constrained()`, or cascade/restrict actions. The application adds them.
- Indexing is allowed: use the normal column type (`unsignedBigInteger`, `ulid`) with `->index()` or `$table->index(...)`. Do not use `foreignId()` or `foreignUlid()`.
- `down()` drops only what the package created.

### Exceptions and tests

Each base exception **class** owns its own 100-code range; there is no package-wide range. Read `rules/20-exceptions.md` and claim the next free block before adding a new exception file.

After every task, run `./vendor/bin/pest --parallel --tia`; fall back to `./vendor/bin/pest` when the failure detail is not enough, TIA is stale, or config, composer, phpunit, bootstrap, migrations, or env changed.
