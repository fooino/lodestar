<?php

namespace Fooino\Lodestar\Tests\Unit;

use Fooino\Lodestar\Support\Filesystem;
use RuntimeException;

describe('Filesystem', function () {

    it('reads, writes, lists, and copies files', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);
        $files->directory(path: $root . '/nested');
        $files->directory(path: $root . '/fake.md');

        $files->write(path: $root . '/b.md', contents: 'b');
        $files->write(path: $root . '/a.md', contents: 'a');
        $files->write(path: $root . '/nested/c.md', contents: 'c');

        try {

            expect($files->read(path: $root . '/a.md'))->toBe('a');

            expect($files->isFile(path: $root . '/a.md'))->toBeTrue();

            expect($files->isDirectory(path: $root . '/nested'))->toBeTrue();

            expect($files->files(pattern: $root . '/*.md'))->toBe([
                $root . '/a.md',
                $root . '/b.md',
            ]);

            $files->copy(from: $root . '/a.md', to: $root . '/copy.md');

            expect($files->read(path: $root . '/copy.md'))->toBe('a');

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('deletes files and directories', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root . '/nested');
        $files->write(path: $root . '/nested/file.md', contents: 'x');

        try {

            $files->delete(path: $root . '/nested');

            expect($files->isDirectory(path: $root . '/nested'))->toBeFalse();

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('throws for disallowed file types', function () {

        $files = new Filesystem();

        expect(fn() => $files->files(pattern: dirname(path: __DIR__, levels: 2) . '/bin/*.php'))
            ->toThrow(
                RuntimeException::class,
                'Unsupported file type'
            );
    });

    it('refuses to unlink a directory', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);

        try {

            expect(fn() => $files->unlink(path: $root))
                ->toThrow(
                    RuntimeException::class,
                    'Refusing to unlink non-file'
                );

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('ignores missing paths when deleting', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);

        try {

            $files->delete(path: $root . '/missing.md');

            expect($files->isFile(path: $root . '/missing.md'))->toBeFalse();

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('only allows the supported extensions', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);
        $files->write(path: $root . '/data.json', contents: '{}');
        $files->write(path: $root . '/config.jsonc', contents: '{}');
        $files->write(path: $root . '/readme.md', contents: '# hi');
        $files->write(path: $root . '/rule.mdc', contents: '# rule');

        try {

            expect($files->read(path: $root . '/data.json'))->toBe('{}');
            expect($files->read(path: $root . '/config.jsonc'))->toBe('{}');
            expect($files->read(path: $root . '/readme.md'))->toBe('# hi');
            expect($files->read(path: $root . '/rule.mdc'))->toBe('# rule');

            expect(fn() => $files->read(path: $root . '/.env'))
                ->toThrow(
                    RuntimeException::class,
                    'Unsupported file type'
                );

            expect(fn() => $files->write(path: $root . '/evil.php', contents: '<?php'))
                ->toThrow(
                    RuntimeException::class,
                    'Unsupported file type'
                );

            expect(fn() => $files->copy(from: $root . '/readme.md', to: $root . '/evil.php'))
                ->toThrow(
                    RuntimeException::class,
                    'Unsupported file type'
                );

            expect($files->isFile(path: $root . '/evil.php'))->toBeFalse();

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('refuses to read through a symlink', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);
        $files->write(path: $root . '/secret.md', contents: 'secret');
        $files->link(target: $root . '/secret.md', link: $root . '/AGENTS.md');

        try {

            expect(fn() => $files->read(path: $root . '/AGENTS.md'))
                ->toThrow(
                    RuntimeException::class,
                    'Refusing symlink'
                );

            //
        } finally {

            removeDirectory(path: $root);
        }
    });

    it('throws when filesystem operations fail', function () {

        $root = sys_get_temp_dir() . '/fooino-standards-' . uniqid();
        $files = new Filesystem();

        $files->directory(path: $root);
        $files->write(path: $root . '/file.md', contents: 'x');

        try {

            set_error_handler(static fn(int $severity, string $message): bool => true);

            try {

                expect(fn() => $files->read(path: $root . '/missing.md'))
                    ->toThrow(
                        RuntimeException::class,
                        'Unable to read file'
                    );

                expect(fn() => $files->write(path: $root . '/missing/child.md', contents: 'x'))
                    ->toThrow(
                        RuntimeException::class,
                        'Unable to write file'
                    );

                expect(fn() => $files->copy(from: $root . '/missing.md', to: $root . '/copy.md'))
                    ->toThrow(
                        RuntimeException::class,
                        'Unable to copy file'
                    );

                expect(fn() => $files->directory(path: $root . '/file.md'))
                    ->toThrow(
                        RuntimeException::class,
                        'Unable to create directory'
                    );

                expect(fn() => $files->link(target: $root . '/file.md', link: $root . '/file.md'))
                    ->toThrow(
                        RuntimeException::class,
                        'Unable to link'
                    );

                //
            } finally {

                restore_error_handler();
            }
        } finally {

            removeDirectory(path: $root);
        }
    });
});
