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
    public function login(array $params): ?UserModel
    {
        // try to find the user by their email
        $found = UserModel::where('email', '=', $params['email'])->first();
        // if we find the user, verify their password matches
        if ($found) {
            $verified = password_verify($params['password'], $found->password);
            $this->logger->info("User ID: $found->id logged in.");
            return $found;
        }

        return $this->create($params);
    }

    /**
     * Create a new User
     * 
     * @param array $params
     */
    public function create(array $params): UserModel
    {
        $user = new UserModel();
        $user->email = $params['email'];
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        $user->save();
        $this->logger->info("User ID: $user->id created.");
        return $user;
    }
}