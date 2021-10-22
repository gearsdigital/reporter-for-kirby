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

class BitbucketReportTest extends TestCase
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
            "id" => 46,
            "repository" => [
                "links" => [
                    "html" => [
                        "href" => "https://bitbucket.org/test/test-repo",
                    ],
                ],
            ],
        ]);
    }

    public function test_should_send_form_data_to_bitbucket_api()
    {
        $mock = new MockHandler([new Response(201, [], $this->response),]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $reporter = new BitbucketReport($this->vendor);
        $reporter->setClient($client);
        $response = $reporter->report([
            'title' => 'Lorem',
            'formFields' => [
                'description' => 'Test',
            ],
        ]);

        // request
        $this->assertEquals("/2.0/repositories/test/test-repo/issues", $mock->getLastRequest()->getUri()->getPath());
        $this->assertEquals('Basic dGVzdC1kZXY6MTIzNDU2Nzg5MA==', $mock->getLastRequest()->getHeader("Authorization")[0]);
        $this->assertEquals('application/json', $mock->getLastRequest()->getHeader("Content-Type")[0]);

        // response
        $this->assertInstanceOf(ReportResponse::class, $response);
        $this->assertEquals(201, $response->status);
        $this->assertEquals(46, $response->issueId);
        $this->assertEquals("https://bitbucket.org/test/test-repo/issues/46", $response->issueUrl);
    }
}
