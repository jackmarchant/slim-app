<?php

namespace App\Email;

class SignedUp extends Email {
    
    /** @var string */
    const TEMPLATE_ID = 'd-766c7305dccf4fd48b686c83f8905e58';

    public function __construct(string $email, array $substitutions = [])
    {
        parent::__construct($email, $substitutions);
        $this->email->setTemplateId(self::TEMPLATE_ID);
        return $this;
    }
}