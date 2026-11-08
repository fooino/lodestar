# 30 — Testing

## Stack

Pest 5 with `orchestra/testbench`. Type coverage through `pest-plugin-type-coverage`. Providers are registered in `getPackageProviders()`.

## Structure

- One test file per source class: `tests/Unit/<Class>UnitTest.php`.
- Inline fixtures and model stubs at the top of the file.
- Large assertion tables(more than 10): static methods in `tests/Data/Datasets.php`.
- Small assertion tables(less than 10): use `foreach(['1.1' => '1', '2' => '2', '3.5' => '3'] as $number => $expected)`.
- Reusable contract fakes: `tests/Fakes/`.
- Every Eloquent fixture uses `Modelable`.

## Naming

- Group with `describe('<Subject>', ...)`.
- New tests use `it('<behavior>', ...)`. Existing `test()` calls in core may remain; migrate only the file you touch.
- Names describe behavior: `it('rejects a negative precision')`.

## Datasets

Do not use Pest's `->with()` for assertion tables. Each row becomes a test case and multiplies setup cost. Use one test with `foreach`:

```php
it('converts every scientific number', function () {

    foreach (Datasets::mathConvertScientificNumber() as $number => $expected) {
    
        expect(convertScientificNumber(number: $number))
            ->toBe($expected);
    }
});
```

## Assertions

- Use Pest expectations first: `expect($value)->toBe($expected)`, `->toThrow(...)`, `->toHaveCount(...)`.
- Use PHPUnit's `$this->assert*()` only when Pest has no equivalent. Do not mix Pest and PHPUnit assertions in one test without a reason.

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

- Do not mock what the package owns; use Testbench and a real SQLite database.
- Core tests must not need a database or an HTTP request.
- Use `Event::fake()` and `Queue::fake()` for side effects.
- No `sleep()`, network calls, or order-dependent tests.
- Architecture tests are part of the suite; add an `arch()` rule for every new directory.
