<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProfileController
{
    /* @var \Slim\Views\Twig */
    protected $view;

    /**
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
      $this->view = $c->get('view');
      $this->logger = $c->get('logger');
      $this->userService = $c->get('service.user');
    }

    /**
     * The index route for the app
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $username = $args['username'];
        $user = $this->userService->findByUsername($args['username']);

        $loggedIn = $this->userService->loggedIn();

        if (!$user) {
            return $this->view->render($response, '404.twig');
        }

        return $this->view->render(
            $response, 
            'profile/index.twig', 
            ['user' => $user, 'showNav' => $loggedIn]
        );
    }
}
