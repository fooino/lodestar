<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;

class WriteAgentsFileTask
{
    /**
     * Write the AGENTS.md file
     */
    public function run(string $root, string $content): void
    {
        (new Filesystem())->write(path: $root . '/AGENTS.md', contents: $content);
    }
}
