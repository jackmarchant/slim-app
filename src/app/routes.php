<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\PageController;

/** 
 * Creates a route string with Controller:Action
 */
function route(string $contr, string $action) : string
{
    return "$contr:$action";
}

$app->get('/', route(PageController::class, 'index'));
$app->post('/login', route(PageController::class, 'login'));
$app->get('/dashboard', route(PageController::class, 'dashboard'));