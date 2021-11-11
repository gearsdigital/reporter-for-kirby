<?php

namespace KirbyReporter\Report;

use KirbyReporter\Client\BitbucketReport;
use KirbyReporter\Client\GithubReport;
use KirbyReporter\Client\GitlabReport;
use KirbyReporter\Client\MailReport;
use KirbyReporter\Model\FormData;
use KirbyReporter\Vendor\IssueTracker;
use KirbyReporter\Vendor\Mail;

/**
 * Defines a single report client.
 *
 * @package KirbyReporter\Report
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ReportClient
{
    use ReportTemplateParser;

    public GithubReport|GitlabReport|BitbucketReport|MailReport $client;
    private IssueTracker|Mail $vendor;

    public function __construct(IssueTracker|Mail $vendor)
    {
        $this->vendor = $vendor;

        if ($this->vendor->getName() == 'bitbucket') {
            $this->client = new BitbucketReport($this->vendor);
        }

        if ($this->vendor->getName() == 'github') {
            $this->client = new GithubReport($this->vendor);
        }

        if ($this->vendor->getName() == 'gitlab') {
            $this->client = new GitlabReport($this->vendor);
        }

        if ($this->vendor->getName() == 'mail') {
            $this->client = new MailReport($this->vendor);
        }
    }

    public final function createReport(FormData $formData): ReportResponse
    {
        // just pass form data, mail template is loaded automatically
        if ($this->vendor->getName() == 'mail') {
            return $this->client->report($formData, null);
        }

        // we need to parse the reporer template first because we need to send a plain 'string'
        // to external APIs
        return $this->client->report($formData, $this->parseTemplate($formData));
    }
}
