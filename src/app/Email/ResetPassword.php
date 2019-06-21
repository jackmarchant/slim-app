<?php

namespace App\Email;

class ResetPassword extends Email {
    
    /** @var string */
    const TEMPLATE_ID = 'd-44fc003426b740b78fdc38dfc07bc0bc';

    public function __construct(string $email, array $substitutions)
    {
        parent::__construct($email, $substitutions);
        $this->email->setTemplateId(self::TEMPLATE_ID);
        $this->email->addSubstitution('url', $substitutions['url']);
        return $this;
    }
}