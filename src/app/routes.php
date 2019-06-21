<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\PageController;

/** 
 * Creates a route string with Controller:Action
 * 
 * @param string $controller A Controller
 * @param string $action The Controller's handler function
 */
function route(string $controller, string $action) : string
{
    return "$controller:$action";
}

$app->get('/', route(PageController::class, 'index'));
$app->get('/login', route(PageController::class, 'index'));
$app->get('/signup', route(PageController::class, 'signup'));
$app->post('/login', route(PageController::class, 'login'));
$app->get('/dashboard', route(PageController::class, 'dashboard'));
$app->get('/logout', route(PageController::class, 'logout'));