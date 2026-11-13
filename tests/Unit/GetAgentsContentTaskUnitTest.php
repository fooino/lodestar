<?php

namespace Fooino\Lodestar\Tests\Unit;

use Fooino\Lodestar\Support\Filesystem;
use Fooino\Lodestar\Tasks\GetAgentsContentTask;

describe('GetAgentsContentTask', function () {

    it('renders the package details and delta', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $delta = $root . '/delta.md';
        $files = new Filesystem();

        $files->directory(path: $root);
        $files->write(path: $delta, contents: "# Package delta\n- custom rule\n");

        try {

            $content = (new GetAgentsContentTask())->run(package: [
                'title'     => 'Fooino Core',
                'composer'  => 'fooino/core',
                'namespace' => 'Fooino\Core',
                'delta'     => $delta,
            ]);

            expect($content)->toContain('# Fooino Core');
            expect($content)->toContain('`fooino/core`');
            expect($content)->toContain('`Fooino\Core`');
            expect($content)->toContain('# Package delta');
            expect($content)->not->toContain('{{');

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('renders without a delta', function () {

        $content = (new GetAgentsContentTask())->run(package: [
            'title'     => 'Fooino Core',
            'composer'  => 'fooino/core',
            'namespace' => 'Fooino\Core',
            'delta'     => null,
        ]);

        expect($content)->toContain('# Fooino Core');
        expect($content)->not->toContain('{{');
    });
});
