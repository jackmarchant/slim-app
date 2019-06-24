<?php

use PHPUnit\Framework\TestCase;
use App\Email\ResetPassword;

class ResetPasswordTest extends TestCase {
    public function setUp()
    {
        $this->email = new ResetPassword('test@email.com', ['url' => 'http://localhost:8000']);
    }

    public function test_can_get_email()
    {
        $this->assertInstanceOf('SendGrid\Mail\Mail', $this->email->getEmail());
    }

    public function test_can_add_substitutions()
    {
        $personalizations = $this->email->getEmail()->getPersonalizations();
        $this->assertSame($personalizations[0]->getSubstitutions(), ['url' => 'http://localhost:8000']);
    }

    public function test_can_add_to()
    {
        $personalizations = $this->email->getEmail()->getPersonalizations();
        $tos = $personalizations[0]->getTos();
        $this->assertSame($tos[0]->getEmail(), 'test@email.com');
        $this->assertSame($tos[0]->getSubstitutions(), ['url' => 'http://localhost:8000']);
    }
}