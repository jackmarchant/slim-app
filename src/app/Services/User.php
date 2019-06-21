<?php

namespace App\Services;

use Slim\Container;

use App\Model\User as UserModel;

class User {

    /** @var string The key for storing session state */
    const SESSION_KEY = 'user_id';

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
            if ($verified) {
                $this->logger->info("User ID: $found->id logged in.");
                $_SESSION['user_id'] = $found->id;
                return $found;
            }
            return null;
        }

        $user = $this->create($params);
        return $this->login($params);
    }

    /**
     * Create a new User
     * 
     * @param array $params
     */
    public function create(array $params): UserModel
    {
        
        $user = UserModel::create([
            'email' => $params['email'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT),
        ]);
        $this->logger->info("User ID: $user->id created.");
        return $user;
    }

    /** 
     * Determine whether the user is currently logged in
     */
    public function loggedIn(): bool {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /** 
     * Log the user out
     */
    public function logout(): bool {
        unset($_SESSION[self::SESSION_KEY]);
        return !$this->loggedIn();
    }
}