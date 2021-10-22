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
        $stub = $this->createMock(Vendor::class);
        $stub->name = "bitbucket";

        $client = new ReportClient($stub);
        $this->assertInstanceOf(BitbucketReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_create_gitlab_client()
    {
        $stub = $this->createMock(Vendor::class);
        $stub->name = "gitlab";

        $client = new ReportClient($stub);
        $this->assertInstanceOf(GitlabReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_create_github_client()
    {
        $stub = $this->createMock(Vendor::class);
        $stub->name = "github";

        $client = new ReportClient($stub);
        $this->assertInstanceOf(GithubReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_has_report_method()
    {
        $stub = $this->createMock(Vendor::class);
        $stub->name = "github";

        $client = new ReportClient($stub);
        $this->assertTrue(method_exists($client, 'createReport'));
    }
}

