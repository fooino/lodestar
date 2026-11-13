<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;
use RuntimeException;

class WriteKiloFilesTask
{
    /**
     * Write the Kilo config and local rules, agents, and commands
     */
    public function run(string $root): void
    {
        $this->writeConfig(root: $root);

        $this->copy(directory: 'rules', destination: $root . '/.kilo/rules');

        $this->copy(directory: 'agents', destination: $root . '/.kilo/agents');

        $this->copy(directory: 'commands', destination: $root . '/.kilo/commands');
    }

    /**
     * Write kilo.jsonc
     */
    private function writeConfig(string $root): void
    {
        (new Filesystem())->write(
            path: $root . '/kilo.jsonc',
            contents: json_encode(
                value: [
                    '$schema'      => 'https://app.kilo.ai/config.json',
                    'instructions' => $this->instructions(),
                ],
                flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
            ) . "\n",
        );
    }

    /**
     * Build the Kilo rule instructions
     */
    private function instructions(): array
    {
        return array_map(
            callback: fn(string $path): string => '.kilo/rules/' . basename(path: $path),
            array: (new GetRuleFilesTask())->run(),
        );
    }

    /**
     * Copy markdown files from the standards repository into the package
     */
    private function copy(string $directory, string $destination): void
    {
        $files = new Filesystem();

        $source = dirname(path: __DIR__, levels: 2) . '/' . $directory;

        $paths = $files->files(pattern: $source . '/*.md');

        if ($paths === []) {

            throw new RuntimeException(message: 'No files found in ' . $source);
        }

        $files->directory(path: $destination);

        foreach ($files->files(pattern: $destination . '/*.md') as $stale) {

            $files->unlink(path: $stale);
        }

        foreach ($paths as $path) {

            $files->copy(from: $path, to: $destination . '/' . basename(path: $path));
        }
    }
}
