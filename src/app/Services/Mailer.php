<?php

namespace App\Services;

use Slim\Container;
use App\Contracts\MailerContract;
use App\Email\Email;

class Mailer implements MailerContract {

    /**
     * @param ContainerInterface $c
     * TODO: MailAdapter
     */
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Send an email via SendGrid
     * 
     * @param Email $email
     */
    public function send(Email $email): bool
    {
        $response = $this->adapter->send($email->getEmail());

        return $response->statusCode() >= 200 && $response->statusCode() <= 299;
    }
}