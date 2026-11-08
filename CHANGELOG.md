# Changelog

All notable changes to `fooino/lodestar` will be documented in this file.

## 1.0.0 - 2026-11-08

- Renamed the package to `fooino/lodestar`; the PHP namespace is now `Fooino\Lodestar`
- Added the canonical rule set: principles, architecture, exceptions, testing, and Eloquent/data
- Added three agents: architecture guard, contract reviewer, and test engineer
- Added five commands: pre-PR, new error, new package, release, and sync
- Added `targets/<key>/delta.md` package sections for `core`, `base`, and `skeleton`
- `composer ai:sync` runs from a package root and writes only `AGENTS.md`
- `composer ai:kilo`, `ai:cursor`, and `ai:claude` generate local tool adapters
- The CLI uses Symfony Console; `SyncStandardsAction` calls one task per responsibility, and `Tool` maps each tool to its task
- Added Pest tests with 99% coverage; `composer test:coverage` enforces 90%
- `composer ai:sync` now writes all tool adapters by default; `--tool` is optional
- Hardened file writes: invalid composer names are rejected and symlinked targets are never followed
- Added `Support\Filesystem`; all file access now goes through it
- Removed the package registry, lock file, drift check, and package/target arguments
