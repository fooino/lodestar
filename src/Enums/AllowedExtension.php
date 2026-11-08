<?php

namespace Fooino\Lodestar\Enums;

/**
 * File extensions the generator may read or write
 */
enum AllowedExtension: string
{
    case Json     = 'json';
    case Jsonc    = 'jsonc';
    case Markdown = 'md';
    case Mdc      = 'mdc';
}
