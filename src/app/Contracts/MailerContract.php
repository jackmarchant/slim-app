<?php

namespace App\Contracts;

use App\Email\Email;

interface MailerContract {
    public function send(Email $email): bool;
}