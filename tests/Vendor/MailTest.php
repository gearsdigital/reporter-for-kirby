<?php

namespace KirbyReporter\Client;

use KirbyReporter\Vendor\Mail;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase
{

    public function test_should_construct_mail_vendor(): void
    {
        $vendor = new Mail('mail@example.com', 'mail@example.com', 'subject');
        $this->assertEquals("mail", $vendor->getName());
        $this->assertEquals("mail@example.com", $vendor->getFrom());
        $this->assertEquals("mail@example.com", $vendor->getTo());
        $this->assertEquals("subject", $vendor->getSubject());
        $this->assertEquals("text", $vendor->getType());
    }

    public function test_should_construct_mail_vendor_with_type_text(): void
    {
        $vendor = new Mail('mail@example.com', 'mail@example.com', 'subject', 'text');
        $this->assertEquals("text", $vendor->getType());
    }

    public function test_should_construct_mail_vendor_with_type_html(): void
    {
        $vendor = new Mail('mail@example.com', 'mail@example.com', 'subject', 'html');
        $this->assertEquals("html", $vendor->getType());
    }

    public function test_should_construct_mail_vendor_with_unknown_type(): void
    {
        $vendor = new Mail('mail@example.com', 'mail@example.com', 'subject', 'lorem');
        $this->assertEquals("text", $vendor->getType());
    }
}
