<?php

namespace App;

class PageController
{
    protected $view;

    public function __construct($c) {
      $this->view = $c->get('view');
    }
    public function page($request, $response, $args) {
      return $this->view->render($response, 'index.twig');
    }
}