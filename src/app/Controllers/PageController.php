<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PageController
{
    /* @var \Slim\Views\Twig */
    protected $view;

    /**
     * @param ContainerInterface $c
     */
    public function __construct($c)
    {
      $this->view = $c->get('view');
      $this->userService = $c->get('service.user');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function index(Request $request, Response $response, array $args): Response
    {
      return $this->view->render($response, 'login.twig', ['hideNav' => true]);
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $this->userService->login($request->getParams());
        print_r(get_class_methods($response));
        return $response->withRedirect('/dashboard');
    }

    public function dashboard(Request $request, Response $response, array $args): Response
    {
        return $this->view->render($response, 'dashboard.twig');
    }
}