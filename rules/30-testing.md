# 30 — Testing

## Stack

Pest 5 with `orchestra/testbench` in a package and `pest-plugin-laravel` in the application. Type coverage through `pest-plugin-type-coverage`. Package providers are registered in `getPackageProviders()`; application tests extend `Tests\TestCase`.

## Writing tests

To write a test, or edit an existing one, use the `test-engineer` agent. It owns the mechanics — file structure, naming, grouping, datasets, assertions, and fixtures. This page is the policy it must follow.

## Database and services

Default to SQLite with the `array` cache driver. Set it in `phpunit.xml`:

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
    <env name="CACHE_STORE" value="array"/>
    <env name="REDIS_PREFIX" value="fooino-testing"/>
    <env name="CACHE_PREFIX" value="core"/>
</php>
```

The MySQL test database is `fooino_testing`, reached locally as `root` / `123`. In a package, point the `mysql` connection at it in `tests/TestCase.php` with Testbench's `defineEnvironment()`; in the application, set the `MYSQL_TEST_*` values in `phpunit.xml` or `.env.testing`. The dedicated keys keep the SQLite default untouched:

```php
/**
 * Configure the MySQL test connection
 */
protected function defineEnvironment($app): void
{
    $app['config']->set(key: 'database.connections.mysql.database', value: env(key: 'MYSQL_TEST_DATABASE', default: 'fooino_testing'));
    $app['config']->set(key: 'database.connections.mysql.username', value: env(key: 'MYSQL_TEST_USERNAME', default: 'root'));
    $app['config']->set(key: 'database.connections.mysql.password', value: env(key: 'MYSQL_TEST_PASSWORD', default: '123'));
}
```

CI overrides the `MYSQL_TEST_*` values. Redis reads `REDIS_HOST`, `REDIS_PORT`, and `REDIS_DB` the same way; its key prefix is always `fooino-testing`, while the cache prefix is the package name from `composer.json` — the part after `fooino/`, such as `core`, `base`, or `media` — or the application name inside an application.

A test that needs real engine behavior — full-text search, a `MATCH … AGAINST` query, a Redis-only command — may use it, but it must guard itself first. Put one guard per service in `tests/Pest.php` and call it before touching the service:

```php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\Assert;
use Throwable;

/**
 * Skip unless the dedicated MySQL test database is reachable
 */
function requireMysql(): void
{
    $connection = DB::connection('mysql');

    if ($connection->getDatabaseName() !== 'fooino_testing') {

        Assert::markTestSkipped('MySQL connection is not the fooino_testing database');
    }

    try {

        $connection->getPdo();

        // 
    } catch (Throwable $exception) {

        Assert::markTestSkipped('MySQL is not available: ' . $exception->getMessage());
    }
}

/**
 * Skip unless Redis is reachable
 */
function requireRedis(): void
{
    try {

        Redis::connection()->ping();

        // 
    } catch (Throwable $exception) {

        Assert::markTestSkipped('Redis is not available: ' . $exception->getMessage());
    }
}
```

```php
it('finds invoices with a full-text search', function () {

    requireMysql();

    expect(searchInvoices(text: 'lorem'))->toHaveCount(1);
});
```

- Read the database name from the connection config before connecting: `fooino_testing` is the only MySQL database a test may use, so a mismatch skips instead of touching development data.
- Leave the database empty when you finish: wrap the test in a transaction or truncate the tables it touched; never assume it is empty when you arrive.
- Do not replace the service with a fake to avoid the guard. If the test exists because MySQL or Redis behaves differently, it runs there or skips.

## Cross-package contracts

- A consumer package fakes the contract (`$this->mock(Media::class, ...)` or `$this->app->instance(Media::class, $fake)`).
- Never add the implementation package to `require` or `require-dev`; that recreates the coupling.
- The application owns the integration test that binds the real implementation.
- Test the optional path: unbound contract, missing capability, or an availability method.
- Mock only the contract boundary, never the class under test.

## Coverage

After every task, run the package tests before reporting done:

```bash
./vendor/bin/pest --parallel --tia     # first run after every task
./vendor/bin/pest                      # full run when TIA is not enough
./vendor/bin/pest --coverage --min=95
./vendor/bin/pest --type-coverage --min=90
```

- Every new or changed line needs a test. Never lower a threshold.
- If TIA's failure detail is not enough to diagnose, run `./vendor/bin/pest`.
- Also fall back to a full run when TIA looks stale, or config, `composer.json`, `phpunit.xml`, the bootstrap, migrations, or env changed.

## Rules

- Do not mock what the package owns; run it against the real database or service.
- Use `Event::fake()` and `Queue::fake()` for side effects.
- No `sleep()`, network calls, or order-dependent tests.
- Architecture tests are part of the suite; add an `arch()` rule for every new directory.
