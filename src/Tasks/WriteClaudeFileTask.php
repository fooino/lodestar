<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;

class WriteClaudeFileTask
{
    /**
     * Write the Claude instructions
     */
    public function run(string $root): void
    {
        (new Filesystem())->write(path: $root . '/CLAUDE.md', contents: $this->content());
    }

    /**
     * Return the Claude instructions content
     */
    private function content(): string
    {
        return <<<'TEXT'
@AGENTS.md

# Fooino standards

Before any task, read the canonical rules under `vendor/fooino/lodestar/rules/`.
If that directory is missing, run `composer install` or read `../lodestar/rules/`.
TEXT;
    }
}
