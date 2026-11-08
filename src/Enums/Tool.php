<?php

namespace Fooino\Lodestar\Enums;

use Fooino\Lodestar\Tasks\WriteClaudeFileTask;
use Fooino\Lodestar\Tasks\WriteCursorFileTask;
use Fooino\Lodestar\Tasks\WriteKiloFilesTask;

/**
 * Supported tool adapters and the task that writes each one
 */
enum Tool: string
{
    case Kilo = 'kilo';
    case Cursor = 'cursor';
    case Claude = 'claude';
    case All = 'all';

    /**
     * Return the labelled tasks for this tool
     */
    public function tasks(): array
    {
        if ($this === self::All) {

            return $this->all();
        }

        return $this->task();
    }

    /**
     * Return the task for a concrete tool
     */
    private function task(): array
    {
        return match ($this) {
            self::Kilo   => ['kilo adapter' => new WriteKiloFilesTask()],
            self::Cursor => ['cursor adapter' => new WriteCursorFileTask()],
            self::Claude => ['claude adapter' => new WriteClaudeFileTask()],
        };
    }

    /**
     * Return every concrete tool task
     */
    private function all(): array
    {
        $tasks = [];

        foreach (self::cases() as $tool) {

            if ($tool === self::All) {

                continue;
            }

            $tasks = [...$tasks, ...$tool->task()];
        }

        return $tasks;
    }
}
