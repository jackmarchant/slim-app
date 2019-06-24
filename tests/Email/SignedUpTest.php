<?php

use PHPUnit\Framework\TestCase;
use App\Email\SignedUp;

class SignedUpEmailTest extends TestCase {
    public function setUp()
    {
        $this->email = new SignedUp('test@email.com');
    }

    public function test_can_get_email()
    {
        $this->assertInstanceOf('SendGrid\Mail\Mail', $this->email->getEmail());
    }

    public function test_can_add_to()
    {
        $personalizations = $this->email->getEmail()->getPersonalizations();
        $tos = $personalizations[0]->getTos();
        $this->assertSame($tos[0]->getEmail(), 'test@email.com');
    }
}