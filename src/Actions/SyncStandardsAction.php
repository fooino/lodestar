<?php

namespace Fooino\Lodestar\Actions;

use Fooino\Lodestar\Enums\Tool;
use Fooino\Lodestar\Tasks\GetAgentsContentTask;
use Fooino\Lodestar\Tasks\GetCurrentPackageTask;
use Fooino\Lodestar\Tasks\WriteAgentsFileTask;

class SyncStandardsAction
{
    /**
     * Generate AGENTS.md and the optional tool adapters for the current package
     */
    public function run(Tool $tool = Tool::All): array
    {
        $package = (new GetCurrentPackageTask())->run();

        $this->writeAgentsMdFile(package: $package);

        return ['AGENTS.md', ...$this->tools(tool: $tool, root: $package['root'])];
    }

    /**
     * Write AGENTS.md for the package
     */
    private function writeAgentsMdFile(array $package): void
    {
        $content = (new GetAgentsContentTask())->run(package: $package);

        (new WriteAgentsFileTask())->run(root: $package['root'], content: $content);
    }

    /**
     * Run the tasks for the selected tool
     */
    private function tools(Tool $tool, string $root): array
    {
        $updated = [];

        foreach ($tool->tasks() as $label => $task) {

            $task->run(root: $root);

            $updated[] = $label;
        }

        return $updated;
    }
}
