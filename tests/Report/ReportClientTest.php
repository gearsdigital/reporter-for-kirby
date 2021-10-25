<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

namespace KirbyReporter\Mixins;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use KirbyReporter\Client\BitbucketReport;
use KirbyReporter\Client\GithubReport;
use KirbyReporter\Client\GitlabReport;
use KirbyReporter\Exception\ReportClientException;
use KirbyReporter\Model\FormData;
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
        $vendor = new Vendor('https://gitlab.com/test/test-repo', '1234567890');
        $client = new ReportClient($vendor);
        $this->assertInstanceOf(GitlabReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_create_github_client()
    {
        $vendor = new Vendor('https://github.com/test/test-repo', '1234567890');
        $client = new ReportClient($vendor);
        $this->assertInstanceOf(GithubReport::class, $client->client);
        $this->assertTrue(method_exists($client->client, 'report'));
    }

    public function test_has_report_method()
    {
        $vendor = new Vendor('https://github.com/test/test-repo', '1234567890');
        $client = new ReportClient($vendor);
        $this->assertTrue(method_exists($client, 'createReport'));
    }

    public function test_throws_ReportClientException_auth_fail()
    {
        $this->test_exeption(401, 'reporter.form.error.authFailed');
    }

    public function test_throws_ReportClientException_not_found()
    {
        $this->test_exeption(404, 'reporter.form.error.repoNotFound');
    }

    public function test_throws_ReportClientException_bad_request()
    {
        $this->test_exeption(400, 'reporter.form.error.badRequest');
    }

    /** @dataProvider statusCodeProvider */
    public function test_throws_ReportClientException_for_unhandled(int $statusCode)
    {
        $this->test_exeption($statusCode, 'reporter.form.error.apiCommunicationError');
    }

    private function test_exeption(int $code, string $message)
    {
        $vendor = new Vendor('https://bitbucket.org/test/test-repo', '1234567890');
        $mock = new MockHandler([new Response($code)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $formData = new FormData([]);
        $reporter = new BitbucketReport($vendor);
        $reporter->setClient($client);

        $this->expectException(ReportClientException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($code);

        $reporter->report($formData);
    }

    public function statusCodeProvider(): array
    {
        return [
            [405],
            [500],
            [501],
        ];
    }
}

