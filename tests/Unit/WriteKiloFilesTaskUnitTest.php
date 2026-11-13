<?php

namespace Fooino\Lodestar\Tests\Unit;

use Fooino\Lodestar\Support\Filesystem;
use Fooino\Lodestar\Tasks\WriteKiloFilesTask;

describe('WriteKiloFilesTask', function () {

    it('writes a kilo config listing every rule', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);

        try {

            (new WriteKiloFilesTask())->run(root: $root);

            $config = json_decode(
                json: $files->read(path: $root . '/kilo.jsonc'),
                associative: true,
                flags: JSON_THROW_ON_ERROR,
            );

            expect($config)->toHaveKey('$schema', 'https://app.kilo.ai/config.json');

            expect($config['instructions'])->toBe(
                array_map(
                    callback: fn(string $path): string => '.kilo/rules/' . basename(path: $path),
                    array: $files->files(pattern: dirname(path: __DIR__, levels: 2) . '/rules/*.md'),
                ),
            );

            //
        } finally {

            removeDirectory(path: $root);
        }
    });
    it('copies every managed directory unchanged', function (string $directory) {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);

        $sources = $files->files(pattern: dirname(path: __DIR__, levels: 2) . '/' . $directory . '/*.md');

        expect($sources)->not->toBeEmpty();

        try {

            (new WriteKiloFilesTask())->run(root: $root);

            $copied = $files->files(pattern: $root . '/.kilo/' . $directory . '/*.md');

            expect($copied)->toHaveCount(count(value: $sources));

            foreach ($sources as $source) {

                $target = $root . '/.kilo/' . $directory . '/' . basename(path: $source);

                expect($files->read(path: $target))->toBe($files->read(path: $source));
            }

            //
        } finally {

            removeDirectory(path: $root);
        }
    })
        ->with([
            'rules'    => ['rules'],
            'agents'   => ['agents'],
            'commands' => ['commands'],
        ]);

    it('removes stale files before copying', function (string $directory) {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root . '/.kilo/' . $directory);
        $files->write(path: $root . '/.kilo/' . $directory . '/999-stale.md', contents: 'stale');

        try {

            (new WriteKiloFilesTask())->run(root: $root);

            $sources = $files->files(pattern: dirname(path: __DIR__, levels: 2) . '/' . $directory . '/*.md');

            expect($files->isFile(path: $root . '/.kilo/' . $directory . '/999-stale.md'))->toBeFalse();
            expect($files->files(pattern: $root . '/.kilo/' . $directory . '/*.md'))->toHaveCount(count(value: $sources));

            //
        } finally {

            removeDirectory(path: $root);
        }
    })
        ->with([
            'rules'    => ['rules'],
            'agents'   => ['agents'],
            'commands' => ['commands'],
        ]);
});
