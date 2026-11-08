<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;

class GetRuleFilesTask
{
    /**
     * Return the sorted rule files
     */
    public function run(): array
    {
        return (new Filesystem())->files(pattern: dirname(path: __DIR__, levels: 2) . '/rules/*.md');
    }
}
