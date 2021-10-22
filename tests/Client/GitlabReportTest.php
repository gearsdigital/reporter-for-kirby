<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

namespace KirbyReporter\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Vendor\Vendor;
use PHPUnit\Framework\TestCase;

class GitlabReportTest extends TestCase
{
    private Vendor $vendor;
    private string $response;

    public function setUp(): void
    {
        $this->vendor = $this->createMock(Vendor::class);
        $this->vendor->owner = 'test';
        $this->vendor->user = 'test-dev';
        $this->vendor->token = '1234567890';
        $this->vendor->repository = 'test-repo';

        $this->response = json_encode([
            "iid" => 46,
            "web_url" => "https://gitlab.com/test/test-repo/issues/46",
        ]);
    }

    public function test_should_send_form_data_to_gitlab_api()
    {
        $mock = new MockHandler([new Response(201, [], $this->response)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $reporter = new GitlabReport($this->vendor);
        $reporter->setClient($client);
        $response = $reporter->report([
            'title' => 'Lorem',
            'formFields' => [
                'description' => 'Test',
            ],
        ]);

        // request
        $this->assertEquals("/api/v4/projects/test%2Ftest-repo/issues", $mock->getLastRequest()->getUri()->getPath());
        $this->assertEquals('1234567890', $mock->getLastRequest()->getHeader("Private-Token")[0]);
        $this->assertEquals('application/json', $mock->getLastRequest()->getHeader("Content-Type")[0]);

        // response
        $this->assertInstanceOf(ReportResponse::class, $response);
        $this->assertEquals(201, $response->status);
        $this->assertEquals(46, $response->issueId);
        $this->assertEquals("https://gitlab.com/test/test-repo/issues/46", $response->issueUrl);
    }
}
