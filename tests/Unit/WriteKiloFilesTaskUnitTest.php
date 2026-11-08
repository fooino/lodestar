<?php

use Fooino\Lodestar\Support\Filesystem;
use Fooino\Lodestar\Tasks\WriteKiloFilesTask;

describe('WriteKiloFilesTask', function () {

    it('removes stale files before copying', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root . '/.kilo/rules');
        $files->write(path: $root . '/.kilo/rules/999-stale.md', contents: 'stale');

        try {

            (new WriteKiloFilesTask())->run(root: $root);

            expect($files->isFile(path: $root . '/.kilo/rules/999-stale.md'))->toBeFalse();
            expect($root . '/.kilo/rules/00-principles.md')->toBeFile();
        } finally {

            removeDirectory(path: $root);
        }
    });
});
