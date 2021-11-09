<?php

namespace KirbyReporter\Client;

use Kirby\Cms\App;
use KirbyReporter\Model\FormData;
use KirbyReporter\Vendor\Mail;
use PHPUnit\Framework\TestCase;

class MailReportTest extends TestCase
{

    private array $defaultEmailArgs = [
        'from' => 'from@example.com',
        'to' => 'to@example.com',
        'subject' => 'subject',
        'data' => [
            'title' => 'Lorem',
            'fields' => [
                'description' => 'Test',
            ],
        ],
    ];

    private array $formData = [
        'title' => 'Lorem',
        'formFields' => [
            'description' => 'Test',
        ],
    ];

    public function test_should_send_mail_with_text_mail_template(): void
    {
        $expectedArgs = array_merge(['template' => 'reporttext'], $this->defaultEmailArgs);
        $mock = $this->getMockBuilder(App::class)->onlyMethods(['email'])->getMock();
        $mock->expects($this->exactly(1))->method('email')->with($expectedArgs);

        $formData = new FormData($this->formData);
        $mail = new Mail("from@example.com", "to@example.com", "subject");
        $client = new MailReport($mail);
        $client->report($formData, null)->toJson();
    }

    public function test_should_send_mail_with_html_mail_template(): void
    {
        $expectedArgs = array_merge(['template' => 'report'], $this->defaultEmailArgs);
        $mock = $this->getMockBuilder(App::class)->onlyMethods(['email'])->getMock();
        $mock->expects($this->exactly(1))->method('email')->with($expectedArgs);

        $formData = new FormData($this->formData);
        $mail = new Mail("from@example.com", "to@example.com", "subject", 'html');
        $client = new MailReport($mail);
        $client->report($formData, null)->toJson();
    }
}
