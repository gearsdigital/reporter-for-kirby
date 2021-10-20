<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

namespace KirbyReporter\Mixins;

use KirbyReporter\Payload\Payload;
use KirbyReporter\Template\TemplateRenderer;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{

    public function test_payload()
    {
        $renderer = $this->createMock(TemplateRenderer::class);
        $renderer->method('renderReportTemplate')->willReturn('renderedReportTemplate');
        $requestData = ['title' => 'Lorem', 'description' => "Hello World"];
        $payload = new Payload($requestData, $renderer);

        // The reason why $requestData["description"] is ignored by TemplateRenderer is that we do not
        // want to test kirby's template interpolation.
        $this->assertEquals(['title' => 'Lorem', 'description' => "renderedReportTemplate"], $payload->payload);
    }
}


