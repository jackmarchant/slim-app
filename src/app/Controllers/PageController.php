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
        if (isset($_SESSION['user_id'])) {
            $this->logger->info('Redirecting logged in user');
            return $response->withRedirect('/dashboard');
        }
        
        return $this->view->render($response, 'login.twig', ['hideNav' => true]);
    }

    /**
     * Handles a POST request for the user to login
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function login(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParams();
        if ($this->userService->login($params)) {
            return $response->withRedirect('/dashboard');
        }

        return $this->view->render($response, 'login.twig', [
          'error' => 'Sorry, your email or password is incorrect. Please try again',
          'hideNav' => true,
          'email' => $params['email'],
        ]);
    }

    /**
     * Logout a logged in user
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function logout(Request $request, Response $response, array $args): Response
    {
        if ($this->userService->logout()) {
          return $this->view->render($response, 'login.twig', [
            'hideNav' => true, 
            'success' => 'You have successfully logged out.'
          ]);
        }
    }

    /**
     * The main route for the app, when user is logged in
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function dashboard(Request $request, Response $response, array $args): Response
    {
        if ($this->userService->loggedIn()) {
            return $this->view->render($response, 'dashboard.twig');
        }

        return $response->withRedirect('/');
    }
}