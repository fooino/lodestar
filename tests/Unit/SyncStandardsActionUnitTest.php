<?php

use Fooino\Lodestar\Actions\SyncStandardsAction;
use Fooino\Lodestar\Enums\Tool;
use Fooino\Lodestar\Support\Filesystem;

describe('SyncStandardsAction', function () {

    it('writes AGENTS.md and every adapter by default', function () {

        $cwd = getcwd();
        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);
        $files->write(
            path: $root . '/composer.json',
            contents: json_encode(value: ['name' => 'fooino/core']),
        );

        chdir(directory: $root);

        try {

            expect((new SyncStandardsAction())->run())->toBe([
                'AGENTS.md',
                'kilo adapter',
                'cursor adapter',
                'claude adapter',
            ]);
            expect($files->read(path: $root . '/AGENTS.md'))->toContain('# Fooino Core');
            expect($root . '/kilo.jsonc')->toBeFile();
            expect($root . '/.kilo/rules/00-principles.md')->toBeFile();
            expect($root . '/.kilo/agents/architecture-guard.md')->toBeFile();
            expect($root . '/.cursor/rules/fooino-standards.mdc')->toBeFile();
            expect($root . '/CLAUDE.md')->toBeFile();

            expect((new SyncStandardsAction())->run(tool: Tool::Kilo))->toBe(['AGENTS.md', 'kilo adapter']);
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });
});
