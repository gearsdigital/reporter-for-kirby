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
use KirbyReporter\Model\FormData;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Vendor\Vendor;
use PHPUnit\Framework\TestCase;

class GithubReportTest extends TestCase
{
    private Vendor $vendor;
    private string $response;

    public function setUp(): void
    {
        $this->vendor = new Vendor('https://github.org/test/test-repo', '1234567890');
        $this->response = json_encode([
            "number" => 46,
            "html_url" => "https://github.com/test/test-repo/issues/46",
        ]);
    }

    public function test_should_send_form_data_to_gitlab_api()
    {
        $mock = new MockHandler([new Response(201, [], $this->response)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $reporter = new GithubReport($this->vendor);
        $reporter->setClient($client);
        $formData = new FormData([
            'title' => 'Lorem',
            'formFields' => [
                'description' => 'Test',
            ],
        ]);
        $response = $reporter->report($formData, 'parsedTemplate');

        // request
        $this->assertEquals("/repos/test/test-repo/issues", $mock->getLastRequest()->getUri()->getPath());
        $this->assertEquals('token 1234567890', $mock->getLastRequest()->getHeader("Authorization")[0]);
        $this->assertEquals('application/json', $mock->getLastRequest()->getHeader("Content-Type")[0]);

        // response
        $this->assertInstanceOf(ReportResponse::class, $response);
        $this->assertEquals(201, $response->status);
        $this->assertEquals(46, $response->issueId);
        $this->assertEquals("https://github.com/test/test-repo/issues/46", $response->issueUrl);
    }
}
