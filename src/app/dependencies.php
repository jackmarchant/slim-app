<?php

/** Monolog */
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

/** Slim */
use Slim\Http\Uri;
use Slim\Http\Environment;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

/** Capsule */
use Illuminate\Database\Capsule\Manager as Capsule;

/** App */
use App\Controllers\PageController;
use App\Services\User as UserService;
use App\Services\Post as PostService;
use App\Services\Mailer;

$container = $app->getContainer();

// Register the logger
$container['logger'] = function($c) {
    $logger = new Logger('log');
    $logger->pushHandler(new ErrorLogHandler());
    return $logger;
};

// Register the database connection
$container['db'] = function($c) {
    $capsule = new Capsule();
    $capsule->addConnection($c->get('settings')['db']);
    $capsule->bootEloquent();
    $capsule->setAsGlobal();
    
    return $capsule;
};

// Register our mailer
$container['mailer'] = function($c) {
    return new Mailer(new \SendGrid(getenv('SENDGRID_API_KEY')));
};

// Register Twig View helper
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Twig($settings['renderer']['template_path']);
    
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = Uri::createFromEnvironment(new Environment($_SERVER));
    $view->addExtension(new TwigExtension($router, $uri));

    return $view;
};

$container['service.user'] = function($c) {
    return new UserService($c);
};

$container['service.post'] = function($c) {
    return new PostService($c);
};

// Register PageController
$container['PageController'] = function($c) {
    return new PageController($c->get('view'));
};