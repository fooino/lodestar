<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;

class WriteCursorFileTask
{
    /**
     * Write the Cursor rule
     */
    public function run(string $root): void
    {
        $path = $root . '/.cursor/rules/fooino-standards.mdc';
        $files = new Filesystem();

        $files->directory(path: dirname(path: $path));

        $files->write(path: $path, contents: $this->content());
    }

    /**
     * Return the Cursor rule content
     */
    private function content(): string
    {
        return <<<'TEXT'
---
description: Fooino package standards. Read AGENTS.md and the canonical rules first.
alwaysApply: true
---

Before planning or editing any file, read:

1. `AGENTS.md` in the project root.
2. The canonical rules under `vendor/fooino/lodestar/rules/`.

If `vendor/fooino/lodestar` is missing, run `composer install` or read
`../lodestar/rules/`. The rules are the source of truth; this file is only a pointer.
TEXT;
    }
}
