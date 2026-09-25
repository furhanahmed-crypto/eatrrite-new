<?php

$dir = __DIR__ . '/home';
$hero = require $dir . '/hero.php';
$services = require $dir . '/services.php';
$mid = require $dir . '/mid.php';
$end = require $dir . '/end.php';

return array_merge(['hero' => $hero, 'services' => $services], $mid, $end);
