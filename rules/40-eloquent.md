# 40 — Eloquent and data

## Models

- Models live in `src/Models/` in a package and `app/Models/` in the application.
- Start a new model from `Model.php.tpl`; fill `{{namespace}}` and `{{class}}`.
- Every project model uses `Fooino\Base\Concerns\Modelable` plus domain concerns.
- Four banner sections in this order, even when empty: Relationships; Accessors, Mutators & Casts; Scopes; Custom Functions.
- Use `#[Guarded('id')]` Never `$guarded = []`.
- Declare `casts()` for `created_at_tz`, `updated_at_tz`, `created_at_ago`, `updated_at_ago`.
- Type every relationship return.
- No business logic in models; custom functions are metadata and helpers only.

## Scopes

- Use `#[Scope]`, not `scopeX`.
- Qualify columns with `$this->qualifyColumn('column')`.
- Delegate generic filters to `Filterable` (`inList`, `inFlag`, comparisons).
- Keep scopes composable and side-effect free.

## Migrations

- One migration per table.
- No foreign keys in package migrations: no `foreign()`, `constrained()`, or cascade/restrict actions. The application adds them.
- Indexing is allowed: use the normal column type (`unsignedBigInteger`, `ulid`) with `->index()` or `$table->index(...)`.
- Conventions: `ulid(..., 26)` for public IDs, `nullableMorphs()` for optional relations, `unsignedTinyInteger` for flags, `created_date` beside `timestamps()` when The number of rows in the table can be very large.
- Add indexes for columns used by scopes in `where` and `orderBy`.
- `down()` drops only what the migration created.

## Queries and writes

- Avoid N+1: eager load relationships used in output.
- Use `runInChunks()` for batch work; never load an unbounded table.
- Use the injected `Mavinoo\Batch\Batch` service (`update(table: $model, values: $chunk, index: 'id')`) for bulk updates instead of updating rows in a loop or using `upsert`. Chunk with `runInChunks()` first.
- Use `firstOrCreate` or `updateOrCreate` for idempotent writes.
- Wrap multi-step writes in `DB::transaction()`.
- Prefer model scopes over raw SQL, and bind every value.

## Data safety

- Guard mass assignment; never expose sequential IDs where a ULID is the public identifier.
- Validate and normalize input before it reaches the model.
- Keep secrets, tokens, and PII out of exception context and logs.
