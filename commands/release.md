---
description: Prepare a Fooino package release with the correct version, changelog, docs, and downstream checks.
---

Read `rules/00-principles.md` and `rules/10-architecture.md` first.

## Versioning

- Semantic versioning: `MAJOR.MINOR.PATCH`.
- Breaking: removed or renamed public method, changed signature or default, changed return type, removed error code, renamed config key, changed schema, or any change to a domain contract in `fooino/base`.
- Additive changes are minor; fixes are patch.
- Changing or removing a contract is breaking for every implementer and consumer; bump `fooino/base` major.

## Changelog

Update `CHANGELOG.md` for every user-visible change:

```markdown
## <version> - <YYYY-MM-DD>

- Added ...
- Changed ...
- Fixed ...
```

Group under Added, Changed, Deprecated, Removed, Fixed, Security. Describe behavior, not the commit.

## Cross-package

When `core` changes:

1. Bump and tag core.
2. Update the `fooino/base` constraint and run its suite.
3. Note the downstream requirement in the core changelog.

When a `base` contract changes:

1. Bump base major and tag it.
2. Release the implementing package against the new contract.
3. Update every consuming package and its tests.
4. Update the application bindings if the contract moved.

When a domain package changes, bump only that package. If it needs a capability from another domain, add the contract to `base` first.

## Checklist

```bash
./vendor/bin/pint --test
./vendor/bin/pest --coverage --min=95
./vendor/bin/pest --type-coverage --min=90
```

Then update the changelog, tag, push, verify Packagist, and announce breaking changes. Do not edit `fooino-docs`; CI publishes docs there.
