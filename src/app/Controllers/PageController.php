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
        
        return $this->view->render($response, 'login.twig');
    }

    /**
     * The signup route for the app
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function signup(Request $request, Response $response, array $args): Response
    {
        if (isset($_SESSION['user_id'])) {
            $this->logger->info('Redirecting logged in user');
            return $response->withRedirect('/dashboard');
        }
        
        return $this->view->render($response, 'signup.twig');
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
      
        if (isset($params['confirmPassword']) && $params['password'] != $params['confirmPassword']) {
          return $this->view->render($response, 'signup.twig', [
            'error' => 'Sorry, those passwords don\'t match. Please try again',
            'email' => $params['email'],
          ]);
        }

        if ($this->userService->login($params)) {
            return $response->withRedirect('/dashboard');
        }

        return $this->view->render($response, 'login.twig', [
          'error' => 'Sorry, your email or password is incorrect. Please try again',
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
            return $this->view->render($response, 'dashboard.twig', ['showNav' => true]);
        }

        return $response->withRedirect('/');
    }

    /**
     * Allow the user to send a password reset link
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function forgotPassword(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParams();
        if (isset($params['email'])) {
            $success = $this->userService->resetPassword($params['email']);
            return $this->view->render($response, 'forgot.twig', [
              'success' => $success ? 'Password reset link sent!' : 'Couldn\'t find that email address',
            ]);
        }

        return $this->view->render($response, 'forgot.twig');
    }

    /**
     * Allow the user to reset their password
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function resetPasswordGet(Request $request, Response $response, array $args): Response
    { 
        $user = $this->userService->findByToken($args['token']);
        
        if (isset($args['token']) && !$user) {
            return $this->view->render($response, 'reset.twig', ['error' => 'Invalid password reset token']);
        }

        return $this->view->render($response, 'reset.twig', ['token' => $args['token']]);
    }

    /**
     * Do the password reset
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function resetPasswordPost(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParams();
        $user = $this->userService->findByToken($params['resetToken']);
        if ($user && $this->userService->updatePassword($user, $params)) {
            return $this->view->render($response, 'login.twig', [
              'email' => $user->email, 
              'success' => 'Password successfully reset'
            ]);
        }

        $token = $params['resetToken'];
        return $response->withRedirect("/reset-password/$token");
    }
}