<?php

/**
 * Live programs from Eat Rrite content handoff.
 * Celiac & Crohn's is intentionally excluded (client dropped).
 */
$programDir = dirname(__DIR__) . '/constants/programs';
$programsLive = [
    require $programDir . '/weight-lifestyle.php',
    require $programDir . '/diabetes.php',
    require $programDir . '/gut-health.php',
    require $programDir . '/female-hormone.php',
];

$programMeta = require $programDir . '/packages.php';
$programPackages = $programMeta['packages'];
$programProcess = $programMeta['process'];

function eatrrite_find_program(string $slug): ?array
{
    global $programsLive;
    foreach ($programsLive as $program) {
        if ($program['slug'] === $slug) {
            return $program;
        }
    }
    return null;
}
