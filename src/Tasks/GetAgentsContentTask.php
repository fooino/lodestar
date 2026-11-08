<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;

class GetAgentsContentTask
{
    /**
     * Build the AGENTS.md content for the package
     */
    public function run(array $package): string
    {
        return strtr($this->template(), $this->replacements(package: $package));
    }

    /**
     * Read the AGENTS.md template
     */
    private function template(): string
    {
        return (new Filesystem())->read(path: dirname(path: __DIR__, levels: 2) . '/templates/AGENTS.md');
    }

    /**
     * Build the template replacements for the package
     */
    private function replacements(array $package): array
    {
        return [
            '{{title}}'       => $package['title'],
            '{{composer}}'    => $package['composer'],
            '{{namespace}}'   => $package['namespace'],
            '{{rules_links}}' => implode(separator: "\n", array: $this->links()),
            '{{delta}}'       => trim(string: (new Filesystem())->read(path: $package['delta'])),
        ];
    }

    /**
     * Build the numbered rule links
     */
    private function links(): array
    {
        $links = [];

        foreach ((new GetRuleFilesTask())->run() as $index => $path) {

            $links[] = ($index + 1) . '. `vendor/fooino/lodestar/rules/' . basename(path: $path) . '`';
        }

        return $links;
    }
}
