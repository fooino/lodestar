---
description: Bootstrap a new Fooino package from the skeleton and add its standards delta.
---

Use this when adding one of the future packages. Read `vendor/fooino/lodestar/rules/10-architecture.md` and `commands/release.md` first.

## Steps

1. Copy `fooino/skeleton` into the new package directory.
2. Decide the layer: put generic, request-free tools in `fooino/core`; put anything that uses the database, models, requests, responses, middleware, auth, or session in `fooino/base` or a domain package.
3. Rename the namespace, package name, provider, config file, and publish tags.
4. Change the runtime requirement to `fooino/base` (which brings `fooino/core`). Do **not** require `fooino/skeleton`.
5. If the package needs a capability from another domain package, add a domain contract (`Media`, `Setting`) under `fooino/base/src/Contracts/` **before** writing the consumer code. Never require the sibling package.
6. Write consumer tests against a fake contract (`$this->mock(Media::class, ...)` or `$this->app->instance(Media::class, $fake)`). Do not add the implementation package to `require-dev`.
7. Remove the domain code you do not need; keep the provider structure. Start models from `templates/Model.php.tpl`.
8. Add `targets/<key>/delta.md` to the standards repository. The key is the package name after `fooino/` in `composer.json`.
9. From the new package root, run `composer ai:sync` and commit `AGENTS.md`.
10. Add the package to the consuming application's `composer.json` only after its first tagged release. The application binds every contract to this package's implementation.

If the package adds an exception file, claim its own 100-code range in `rules/20-exceptions.md` before writing it. There is no package-wide range.

## Guardrails

- Never assign one exception range to two exception files; each class claims its own 100-code block.
- Do not add foreign key constraints to package migrations; the application owns referential integrity.
- Do not ship routes, controllers, gates, or policies. Expose Tasks, Actions, Commands, middleware, requests, models, and contracts; the application invokes them.
- Do not invoke a Task or Action from the provider's `boot()` or `register()`.
- Never require or import a sibling domain package, including as a dev dependency for tests. Use a contract in `fooino/base` and fake it.
- Do not add a dependency on `fooino/base` from `fooino/core`.
- Do not bind a domain package's concrete class inside `fooino/base`; the application owns bindings.
- Keep `AGENTS.md` generated; put package-specific notes in `targets/<key>/delta.md`.
