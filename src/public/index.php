<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$config['displayErrorDetails'] = getenv('DISPLAY_ERRORS');

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('log');
    $logger->pushHandler(new \Monolog\Handler\ErrorLogHandler());
    return $logger;
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('src/app/templates');
    
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$container['PageController'] = function($c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    return new \App\PageController($view);
};

$app->get('/', \App\PageController::class . ':page');

$app->get('/{name}', function (Request $request, Response $response, array $args): Response {
    $name = ucfirst($args['name']);
    $response->getBody()->write('Hello, ' . $name);

    $this->logger->addInfo("A page has been requested for $name");

    return $response;
});
$app->run();