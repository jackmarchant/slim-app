<?php

namespace App\Services;

use Slim\Container;

use App\Model\User as UserModel;

class User {

    /**
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->db = $c->get('db');
        $this->logger = $c->get('logger');
    }

    /**
     * Login the User
     * 
     * @param array $params
     */
    public function login(array $params): UserModel
    {
        $user = new UserModel();
        // TODO: validate params
        $user->email = $params['email'];
        // TODO: hash password
        $user->password = $params['password'];
        // $user->created_at = new \DateTime();
        $user->save();
        $this->logger->info('User created ' . $params);

        return $user;
    }
}