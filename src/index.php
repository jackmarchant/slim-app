<?php

$settings = require __DIR__ . '/app/settings.php';
$app = new \Slim\App($settings);

// Load dependencies
require __DIR__ . '/app/dependencies.php';

// Load routes
require __DIR__ . '/app/routes.php';

$app->run();