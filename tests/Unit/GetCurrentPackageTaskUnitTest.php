<?php

namespace Fooino\Lodestar\Tests\Unit;

use Fooino\Lodestar\Support\Filesystem;
use Fooino\Lodestar\Tasks\GetCurrentPackageTask;
use RuntimeException;

/**
 * Create a temporary package root with an optional composer.json
 */
function makePackageRoot(array|null $composer): string
{
    $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
    $files = new Filesystem();

    $files->directory(path: $root);

    if ($composer !== null) {

        $files->write(
            path: $root . '/composer.json',
            contents: json_encode(value: $composer),
        );
    }

    return $root;
}

describe('GetCurrentPackageTask', function () {

    it('returns the current package details', function () {

        $cwd = getcwd();
        $root = makePackageRoot(composer: ['name' => 'fooino/core']);

        chdir(directory: $root);

        try {

            $package = (new GetCurrentPackageTask())->run();

            expect($package['root'])->toBe(realpath(path: $root));

            expect($package['title'])->toBe('Fooino Core');

            expect($package['composer'])->toBe('fooino/core');

            expect($package['namespace'])->toBe('Fooino\Core');

            expect($package['delta'])->toBe(dirname(path: __DIR__, levels: 2) . '/targets/core/delta.md');

            expect($package['delta'])->toBeFile();

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('fails without composer.json', function () {

        $cwd = getcwd();
        $root = makePackageRoot(composer: null);

        chdir(directory: $root);

        try {

            expect(fn() => (new GetCurrentPackageTask())->run())
                ->toThrow(
                    RuntimeException::class,
                    'No composer.json found'
                );

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('fails without a package name', function () {

        $cwd = getcwd();
        $root = makePackageRoot(composer: []);

        chdir(directory: $root);

        try {

            expect(fn() => (new GetCurrentPackageTask())->run())
                ->toThrow(
                    RuntimeException::class,
                    'missing package name'
                );

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('fails for an invalid package name', function () {

        $cwd = getcwd();
        $root = makePackageRoot(composer: ['name' => 'core']);

        chdir(directory: $root);

        try {

            expect(fn() => (new GetCurrentPackageTask())->run())
                ->toThrow(
                    RuntimeException::class,
                    'Invalid package name'
                );

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('rejects path traversal in the package name', function () {

        $cwd = getcwd();
        $root = makePackageRoot(composer: ['name' => 'fooino/../../etc']);

        chdir(directory: $root);

        try {

            expect(fn() => (new GetCurrentPackageTask())->run())
                ->toThrow(
                    RuntimeException::class,
                    'Invalid package name'
                );

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('fails without a standards delta', function () {

        $cwd = getcwd();
        $root = makePackageRoot(composer: ['name' => 'fooino/unknown']);

        chdir(directory: $root);

        try {

            expect(fn() => (new GetCurrentPackageTask())->run())
                ->toThrow(
                    RuntimeException::class,
                    'No standards delta'
                );

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });
});
