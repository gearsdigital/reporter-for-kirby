<?php

namespace KirbyReporter\Client;

use KirbyReporter\Model\FormData;
use KirbyReporter\Report\ReportInterface;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Vendor\Mail;

class MailReport implements ReportInterface
{

    private Mail $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function report(FormData $reportData, ?string $templateData): ReportResponse
    {
        kirby()->email([
            'template' => $this->mail->getType() == 'html' ? 'report' : 'reporttext',
            'from' => $this->mail->getFrom(),
            'to' => $this->mail->getTo(),
            // @todo evaluate computed options to generate the subject dynamically
            'subject' => $this->mail->getSubject(),
            'data' => [
                'title' => $reportData->getTitle(),
                'fields' => $reportData->getFormFields(),
            ],
        ]);

        return new ReportResponse(200, '', '');
    }
}
