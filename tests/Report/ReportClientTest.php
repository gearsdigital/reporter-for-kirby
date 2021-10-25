<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

namespace KirbyReporter\Mixins;

use KirbyReporter\Client\BitbucketReport;
use KirbyReporter\Client\GithubReport;
use KirbyReporter\Client\GitlabReport;
use KirbyReporter\Report\ReportClient;
use KirbyReporter\Vendor\Vendor;
use PHPUnit\Framework\TestCase;

class ReportClientTest extends TestCase
{
    public function test_create_bitbucket_client()
    {
        $vendor = new Vendor('https://bitbucket.org/test/test-repo', '1234567890', 'test-dev');
        $client = new ReportClient($vendor);
        $this->assertInstanceOf(BitbucketReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_create_gitlab_client()
    {
        $vendor = new Vendor('https://gitlab.org/test/test-repo', '1234567890');
        $client = new ReportClient($vendor);
        $this->assertInstanceOf(GitlabReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_create_github_client()
    {
        $vendor = new Vendor('https://github.org/test/test-repo', '1234567890');
        $client = new ReportClient($vendor);
        $this->assertInstanceOf(GithubReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_has_report_method()
    {
        $vendor = new Vendor('https://github.org/test/test-repo', '1234567890');
        $client = new ReportClient($vendor);
        $this->assertTrue(method_exists($client, 'createReport'));
    }
}

