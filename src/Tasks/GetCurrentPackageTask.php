<?php

namespace Fooino\Lodestar\Tasks;

use Fooino\Lodestar\Support\Filesystem;
use RuntimeException;

class GetCurrentPackageTask
{
    /**
     * Return the current package details from composer.json
     */
    public function run(): array
    {
        $root = $this->root();

        $composer = $this->composer(root: $root);

        $name = (string) $composer['name'];

        [$vendor, $package] = $this->splitName(name: $name);

        $key = strtolower(string: $package);

        $pascal = $this->pascal(value: $key);

        return [
            'root'      => $root,
            'title'     => ucfirst(string: $vendor) . ' ' . $pascal,
            'composer'  => $name,
            'namespace' => ucfirst(string: $vendor) . '\\' . $pascal,
            'delta'     => $this->delta(key: $key),
        ];
    }

    /**
     * Return the current package root
     */
    private function root(): string
    {
        $root = getcwd();

        if ($root === false) {

            throw new RuntimeException(message: 'Unable to resolve the current package root.');
        }

        return $root;
    }

    /**
     * Read and validate the package composer.json
     */
    private function composer(string $root): array
    {
        $path = $root . '/composer.json';
        $files = new Filesystem();

        if (!$files->isFile(path: $path)) {

            throw new RuntimeException(message: 'No composer.json found. Run this command from a package root.');
        }

        $composer = json_decode(
            json: $files->read(path: $path),
            associative: true,
        );

        if (!is_array($composer) || !isset($composer['name'])) {

            throw new RuntimeException(message: 'Invalid composer.json: missing package name.');
        }

        return $composer;
    }

    /**
     * Split the composer package name into vendor and package
     */
    private function splitName(string $name): array
    {
        $parts = explode(separator: '/', string: $name, limit: 2);

        if (
            count(value: $parts) !== 2 ||
            !$this->valid(name: $parts[0]) ||
            !$this->valid(name: $parts[1])
        ) {

            throw new RuntimeException(message: 'Invalid package name: ' . $name);
        }

        return $parts;
    }

    /**
     * Determine whether a composer name segment is valid
     */
    private function valid(string $name): bool
    {
        return preg_match(pattern: '/^[a-z0-9]([_.-]?[a-z0-9]+)*$/', subject: $name) === 1;
    }

    /**
     * Return the standards delta file for the package when it exists
     */
    private function delta(string $key): string|null
    {
        $delta = dirname(path: __DIR__, levels: 2) . '/targets/' . $key . '/delta.md';

        return (new Filesystem())->isFile(path: $delta) ? $delta : null;
    }

    /**
     * Convert a package key to PascalCase
     */
    private function pascal(string $value): string
    {
        return str_replace(
            search: ' ',
            replace: '',
            subject: ucwords(string: str_replace(search: '-', replace: ' ', subject: $value)),
        );
    }
}
