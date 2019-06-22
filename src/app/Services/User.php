<?php

namespace App\Services;

use Slim\Container;

use App\Model\User as UserModel;
use App\Email\ResestPassword;
use \App\Traits\UrlGenerator;

class User {

    use UrlGenerator;

    /** @var string The key for storing session state */
    const SESSION_KEY = 'user_id';

    /**
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->db = $c->get('db');
        $this->logger = $c->get('logger');
        $this->mailer = $c->get('mailer');
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
        $username = $params['username'] ?? 'user' . substr(md5(mt_rand()), 0, 7); 
        $user = UserModel::create([
            'email' => $params['email'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT),
            'username' => $username
        ]);
        $this->mailer->send(new \App\Email\SignedUp($user->email));
        $this->logger->info("User ID: $user->id created.");
        return $user;
    }

    /** 
     * Determine whether the user is currently logged in
     */
    public function loggedIn(): bool 
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /** 
     * Log the user out
     */
    public function logout(): bool 
    {
        unset($_SESSION[self::SESSION_KEY]);
        return !$this->loggedIn();
    }

    /**
     * Generate a password reset token and email
     * 
     * @param string $email 
     */
    public function resetPassword($email): bool
    {
        $user = UserModel::where('email', '=', $email)->first();
        if (!$user) {
            return false;
        }

        $user->password_reset_token = bin2hex(random_bytes(52));
        $user->save();
        $user->refresh();

        return $this->mailer->send(new \App\Email\ResetPassword(
            $user->email,
            ['url' => $this->generateUrl('reset-password/' . $user->password_reset_token)]
        ));
    }

    /**
     * Find a user by their password reset token
     * 
     * @param string $token
     */
    public function findByToken(string $token): ?UserModel
    {
        return UserModel::where('password_reset_token', '=', $token)->first();
    }

    /**
     * Update a user's password
     * 
     * @param ModelUser $user The user to be updated
     * @param array $params An array of attributes
     */
    public function updatePassword(UserModel $user, array $params): bool 
    {
        if (isset($params['password']) && isset($params['confirmPassword']) && (
            $params['password'] == $params['confirmPassword']
        )) {
            $user->password_reset_token = null;
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
            return $user->save();
        }

        return false;
    }

    /**
     * Get the current user
     * 
     * @return UserModel|null
     */
    public function currentUser(): ?UserModel
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        return UserModel::find($_SESSION['user_id']);
    }

    /**
     * Find a user by their username
     * 
     * @param string $username
     */
    public function findByUsername($username): ?UserModel   
    {
        return UserModel::where('username', '=', $username)->first();
    }
}