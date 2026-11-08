<?php

use Fooino\Lodestar\Support\Filesystem;
use Fooino\Lodestar\Tasks\WriteAgentsFileTask;
use RuntimeException;

describe('WriteAgentsFileTask', function () {

    it('refuses to write through a symlink', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();
        $target = $root . '/target.md';

        $files->directory(path: $root);
        $files->write(path: $target, contents: 'original');
        $files->link(target: $target, link: $root . '/AGENTS.md');

        try {

            expect(fn() => (new WriteAgentsFileTask())->run(root: $root, content: 'new'))
                ->toThrow(RuntimeException::class, 'Refusing symlink');
            expect($files->read(path: $target))->toBe('original');
        } finally {

            removeDirectory(path: $root);
        }
    });
});
