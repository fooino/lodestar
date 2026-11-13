---
description: Scaffold a copied skeleton directory into a new Fooino package.
---

The developer creates `packages/<name>` and copies every skeleton file into it before running this command. Run from that directory. Do not clone, copy, or install the skeleton yourself.

## Ask for the name

1. Confirm the current `composer.json` still has `"name": "fooino/skeleton"`. If it does not, stop and report.
2. Ask: **"What is the name of the new package?"** The answer is `{PACKAGE_SLUG}` — lowercase, no spaces, for example `product`.
3. Derive the rest:

| Variable | Rule | Example |
|---|---|---|
| `{PACKAGE_SLUG}` | the answer | `product` |
| `{PACKAGE_NAMESPACE}` | `Str::studly({PACKAGE_SLUG})` | `Product` |
| `{PACKAGE_CONSTANT}` | `Str::upper(Str::snake({PACKAGE_SLUG}))` | `PRODUCT` |
| `{PACKAGE_LOWER}` | `Str::lower({PACKAGE_SLUG})` | `product` |

The composer name is `fooino/{PACKAGE_SLUG}`.

## Rename and replace

Apply in this order and re-read each file after editing. Every `Skeleton`, `skeleton`, and `SKELETON` in code, strings, comments, and paths becomes the matching variable.

### 1. `composer.json`

| From | To |
|---|---|
| `"name": "fooino/skeleton"` | `"name": "fooino/{PACKAGE_SLUG}"` |
| `The fooino skeleton package with tools and functionalities.` | `The fooino {PACKAGE_LOWER} package with tools and functionalities.` |
| `"skeleton"` in `keywords` | `"{PACKAGE_SLUG}"` |
| Every `Fooino\\Skeleton\\` in `autoload`, `autoload-dev`, and `extra` | `Fooino\\{PACKAGE_NAMESPACE}\\` |
| `Providers\\SkeletonServiceProvider` in `extra.laravel.providers` | `Providers\\{PACKAGE_NAMESPACE}ServiceProvider` |

### 2. Provider

Rename `src/Providers/SkeletonServiceProvider.php` to `src/Providers/{PACKAGE_NAMESPACE}ServiceProvider.php`, then:

| From | To |
|---|---|
| `namespace Fooino\Skeleton\Providers;` | `namespace Fooino\{PACKAGE_NAMESPACE}\Providers;` |
| `class SkeletonServiceProvider extends ServiceProvider` | `class {PACKAGE_NAMESPACE}ServiceProvider extends ServiceProvider` |
| Every `fooino-skeleton-{xxx}` (config, migrations, langs, assets, views, publish-all) | `fooino-{PACKAGE_SLUG}-{xxx}` |
| Every `config('fooino-skeleton.{xxx}')` | `config('fooino-{PACKAGE_SLUG}.{xxx}')` |
| `lang_path("vendor/fooino/skeleton")` | `lang_path("vendor/fooino/{PACKAGE_SLUG}")` |
| `public_path('vendor/fooino/skeleton')` | `public_path('vendor/fooino/{PACKAGE_SLUG}')` |
| `resource_path("views/vendor/fooino/skeleton")` | `resource_path("views/vendor/fooino/{PACKAGE_SLUG}")` |
| `SkeletonServiceProvider::class` | `{PACKAGE_NAMESPACE}ServiceProvider::class` |
| `SkeletonEventServiceProvider::class` | `{PACKAGE_NAMESPACE}EventServiceProvider::class` |
| `$this->loadTranslationsFrom(__DIR__ . "/../../lang", 'skeleton');` | `$this->loadTranslationsFrom(__DIR__ . "/../../lang", '{PACKAGE_LOWER}');` |
| `$this->loadViewsFrom(__DIR__ . "/../../resources/views", 'skeleton');` | `$this->loadViewsFrom(__DIR__ . "/../../resources/views", '{PACKAGE_LOWER}');` |
| `__('skeleton::file.key')` in the translation comment | `__('{PACKAGE_LOWER}::file.key')` |
| `skeleton:: prefix` in the view comment | `{PACKAGE_LOWER}:: prefix` |

### 3. Tests

| File | From | To |
|---|---|---|
| `tests/Unit/ArchitectureUnitTest.php` | `namespace Fooino\Skeleton\Tests\Unit;` | `namespace Fooino\{PACKAGE_NAMESPACE}\Tests\Unit;` |
| `tests/Unit/ArchitectureUnitTest.php` | every `Fooino\Skeleton\` in `arch()` expectations | `Fooino\{PACKAGE_NAMESPACE}\` |
| `tests/TestCase.php` | `namespace Fooino\Skeleton\Tests;` | `namespace Fooino\{PACKAGE_NAMESPACE}\Tests;` |
| `tests/TestCase.php` | `use Fooino\Skeleton\Providers\SkeletonServiceProvider;` | `use Fooino\{PACKAGE_NAMESPACE}\Providers\{PACKAGE_NAMESPACE}ServiceProvider;` |
| `tests/TestCase.php` | `SkeletonServiceProvider::class` | `{PACKAGE_NAMESPACE}ServiceProvider::class` |
| `tests/Pest.php` | `Fooino\Skeleton\Tests\TestCase::class` | `Fooino\{PACKAGE_NAMESPACE}\Tests\TestCase::class` |

### 4. `src/helpers.php`

| From | To |
|---|---|
| `FOOINO_SKELETON_CONSTANTS_DEFINED` (both occurrences) | `FOOINO_{PACKAGE_CONSTANT}_CONSTANTS_DEFINED` |

### 5. Config

| File | From | To |
|---|---|---|
| `phpunit.xml` | `name="Skeleton Package Tests"` | `name="{PACKAGE_NAMESPACE} Package Tests"` |
| `phpdoc.dist.xml` | `<title>Skeleton API Documentation</title>` | `<title>{PACKAGE_NAMESPACE} API Documentation</title>` |

### 6. Docs

| File | From | To |
|---|---|---|
| `CHANGELOG.md` | `fooino/skeleton` | `fooino/{PACKAGE_SLUG}` |
| `CONTRIBUTING.md` | `Fooino Skeleton Package` | `Fooino {PACKAGE_NAMESPACE} Package` |
| `README.md` | `# Fooino skeleton package` | `# Fooino {PACKAGE_LOWER} package` |
| `README.md` | `composer require fooino/skeleton` | `composer require fooino/{PACKAGE_SLUG}` |
| `README.md` | `git clone https://github.com/fooino/skeleton.git` | `git clone https://github.com/fooino/{PACKAGE_SLUG}.git` |
| `README.md` | `cd ./packages/skeleton` | `cd ./packages/{PACKAGE_SLUG}` |
| `README.md` | everything from `## 🔄 Scaffold a New Package From This Skeleton` to the end of the file | delete |

### 7. `.github/workflows/main.yml`

| From | To |
|---|---|
| `Copy docs and README to skeleton directory` | `Copy docs and README to {PACKAGE_SLUG} directory` |
| `fooino-docs/skeleton` (every occurrence) | `fooino-docs/{PACKAGE_SLUG}` |
| `git add skeleton/` | `git add {PACKAGE_SLUG}/` |
| `Update skeleton docs from fooino/skeleton@` | `Update {PACKAGE_SLUG} docs from fooino/{PACKAGE_SLUG}@` |
