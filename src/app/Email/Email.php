<?php

namespace App\Email;

use \SendGrid\Mail\Mail;
use \SendGrid\Mail\To;

abstract class Email {
    
    /** @var string */
    const FROM_EMAIL = 'twigger@twigger.com';

    public function __construct(string $to, array $substitutions)
    {
        $this->email = new Mail(); 
        $this->email->setFrom(self::FROM_EMAIL, 'Twigger');
        $this->email->addTo(new To($to, null, $substitutions));
    }

    /** 
     * Get SendGrid Email instance
     */
    public function getEmail(): Mail
    {
        return $this->email;
    }
}