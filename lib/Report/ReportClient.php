<?php

namespace KirbyReporter\Report;

use KirbyReporter\Client\BitbucketReport;
use KirbyReporter\Client\GithubReport;
use KirbyReporter\Client\GitlabReport;
use KirbyReporter\Model\FormData;
use KirbyReporter\Vendor\Vendor;

/**
 * Defines a single report client.
 *
 * @package KirbyReporter\Report
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ReportClient
{
    /**
     * @var BitbucketReport|GithubReport|GitlabReport
     */
    public $client;

    public function __construct(Vendor $vendor)
    {
        if ($vendor->name == 'bitbucket') {
            $this->client = new BitbucketReport($vendor);
        }

        if ($vendor->name == 'github') {
            $this->client = new GithubReport($vendor);
        }

        if ($vendor->name == 'gitlab') {
            $this->client = new GitlabReport($vendor);
        }
    }

    public final function createReport(FormData $formData): ReportResponse
    {
        return $this->client->report($formData);
    }
}
