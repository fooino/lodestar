<?php

namespace Fooino\Lodestar\Tests\Unit;

use Fooino\Lodestar\Console\SyncStandardsCommand;
use Fooino\Lodestar\Support\Filesystem;
use Symfony\Component\Console\Tester\CommandTester;

describe('SyncStandardsCommand', function () {

    it('writes every adapter when the tool option is omitted', function () {

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

            $tester = new CommandTester(command: new SyncStandardsCommand());

            expect($tester->execute(input: []))->toBe(0);
        
            expect($tester->getDisplay())->toContain('AGENTS.md updated');
            expect($tester->getDisplay())->toContain('kilo adapter updated');
            expect($tester->getDisplay())->toContain('cursor adapter updated');
            expect($tester->getDisplay())->toContain('claude adapter updated');
        
            expect($root . '/kilo.jsonc')->toBeFile();
            expect($root . '/.cursor/rules/fooino-standards.mdc')->toBeFile();
            expect($root . '/CLAUDE.md')->toBeFile();

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('only writes the selected adapter', function () {

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

            $tester = new CommandTester(command: new SyncStandardsCommand());

            expect($tester->execute(input: ['--tool' => 'kilo']))->toBe(0);

            expect($tester->getDisplay())->toContain('kilo adapter updated');
            expect($tester->getDisplay())->not->toContain('cursor adapter updated');
            
            expect($root . '/kilo.jsonc')->toBeFile();

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });

    it('returns failure for an unknown tool', function () {

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

            $tester = new CommandTester(command: new SyncStandardsCommand());

            expect($tester->execute(input: ['--tool' => 'nope']))->toBe(1);
            expect($tester->getDisplay())->toContain('Unknown tool: nope');

            //
        } finally {

            chdir(directory: $cwd);

            removeDirectory(path: $root);
        }
    });
});
