<?php

namespace Fooino\Lodestar\Support;

use RuntimeException;

/**
 * Guarded filesystem access for JSON and Markdown files
 */
class Filesystem
{
    /**
     * Read a file
     */
    public function read(string $path): string
    {
        $this->guardExtension(path: $path);
        $this->guard(path: $path);

        $contents = @file_get_contents(filename: $path);

        if ($contents === false) {

            throw new RuntimeException(message: 'Unable to read file: ' . $path);
        }

        return $contents;
    }

    /**
     * Write a file, refusing symlinks
     */
    public function write(string $path, string $contents): void
    {
        $this->guard(path: $path);
        $this->guardExtension(path: $path);

        if (@file_put_contents(filename: $path, data: $contents) === false) {

            throw new RuntimeException(message: 'Unable to write file: ' . $path);
        }
    }

    /**
     * Create a directory when missing, refusing symlinks
     */
    public function directory(string $path): void
    {
        $this->guard(path: $path);

        if (is_dir(filename: $path)) {

            return;
        }

        $parent = dirname(path: $path);

        if ($parent !== $path) {

            $this->directory(path: $parent);
        }

        if (!@mkdir(directory: $path, permissions: 0775, recursive: true)) {

            throw new RuntimeException(message: 'Unable to create directory: ' . $path);
        }
    }

    /**
     * Copy a file, refusing symlinked destinations
     */
    public function copy(string $from, string $to): void
    {
        $this->guardExtension(path: $from);
        $this->guard(path: $from);
        $this->guardExtension(path: $to);
        $this->guard(path: $to);

        if (!@copy(from: $from, to: $to)) {

            throw new RuntimeException(message: 'Unable to copy file: ' . $from . ' to ' . $to);
        }
    }

    /**
     * Return sorted existing files matching a pattern
     */
    public function files(string $pattern): array
    {
        $files = glob(pattern: $pattern);

        if ($files === false) {

            throw new RuntimeException(message: 'Unable to read files: ' . $pattern);
        }

        $files = array_values(
            array_filter(
                array: $files,
                callback: fn(string $path): bool => is_file(filename: $path),
            ),
        );

        foreach ($files as $path) {

            $this->guardExtension(path: $path);
        }

        sort(array: $files);

        return $files;
    }

    /**
     * Determine whether a file exists
     */
    public function isFile(string $path): bool
    {
        return is_file(filename: $path);
    }

    /**
     * Determine whether a directory exists
     */
    public function isDirectory(string $path): bool
    {
        return is_dir(filename: $path);
    }

    /**
     * Unlink a file or symlink, refusing directories
     */
    public function unlink(string $path): void
    {
        if (!is_file(filename: $path) && !is_link(filename: $path)) {

            throw new RuntimeException(message: 'Refusing to unlink non-file: ' . $path);
        }

        if (!@unlink(filename: $path)) {

            throw new RuntimeException(message: 'Unable to unlink: ' . $path);
        }
    }

    /**
     * Delete a file, symlink, or directory recursively
     */
    public function delete(string $path): void
    {
        if (is_link(filename: $path) || is_file(filename: $path)) {

            $this->unlink(path: $path);

            return;
        }

        if (!is_dir(filename: $path)) {

            return;
        }

        $items = @scandir(directory: $path);

        if ($items === false) {

            throw new RuntimeException(message: 'Unable to scan directory: ' . $path);
        }

        foreach ($items as $item) {

            if ($item === '.' || $item === '..') {

                continue;
            }

            $this->delete(path: $path . '/' . $item);
        }

        if (!@rmdir(directory: $path)) {

            throw new RuntimeException(message: 'Unable to remove directory: ' . $path);
        }
    }

    /**
     * Create a symlink, refusing an existing target
     */
    public function link(string $target, string $link): void
    {
        $this->guardExtension(path: $target);
        $this->guardExtension(path: $link);
        $this->guard(path: $link);

        if (!@symlink(target: $target, link: $link)) {

            throw new RuntimeException(message: 'Unable to link: ' . $link);
        }
    }

    /**
     * Refuse unsupported file types
     */
    private function guardExtension(string $path): void
    {
        $extension = strtolower(string: pathinfo(path: $path, flags: PATHINFO_EXTENSION));

        if (!in_array(needle: $extension, haystack: ['json', 'jsonc', 'md', 'mdc'], strict: true)) {

            throw new RuntimeException(message: 'Unsupported file type: ' . $path);
        }
    }

    /**
     * Refuse to use a symlink
     */
    private function guard(string $path): void
    {
        if (is_link(filename: $path)) {

            throw new RuntimeException(message: 'Refusing symlink: ' . $path);
        }
    }
}
