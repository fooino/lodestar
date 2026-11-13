<?php

use Fooino\Lodestar\Support\Filesystem;

/**
 * Remove a test directory recursively
 */
function removeDirectory(string $path): void
{
    $base = realpath(path: sys_get_temp_dir()) . '/fooino-standards-';
    $real = realpath(path: $path);

    if ($real === false || !str_starts_with(haystack: $real, needle: $base)) {

        throw new RuntimeException(message: 'Refusing to remove non-test directory: ' . $path);
    }

    (new Filesystem())->delete(path: $path);
}
