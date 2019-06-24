<?php

$root = __DIR__ . '/../';

require realpath($root) . '/vendor/autoload.php';

$settings = require realpath($root) . '/src/app/settings.php';
$app = new \Slim\App($settings);

session_start([
    'cookie_lifetime' => 86400,
]);

// Load dependencies
require realpath($root) . '/src/app/dependencies.php';

// Load routes
require realpath($root) . '/src/app/routes.php';