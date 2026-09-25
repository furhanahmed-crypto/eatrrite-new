<?php

declare(strict_types=1);

function er_base(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $dir = trim(dirname($script), '/.');
    if ($dir === '' || $dir === '.') {
        $base = '';
        return $base;
    }

    $depth = substr_count($dir, '/') + 1;
    $base = str_repeat('../', $depth);
    return $base;
}

function er_href(string $path): string
{
    return er_base() . ltrim($path, '/');
}
