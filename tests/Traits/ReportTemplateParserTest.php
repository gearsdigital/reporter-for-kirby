<?php

namespace KirbyReporter\Traits;

use Kirby\Toolkit\Tpl;
use KirbyReporter\Model\FormData;
use KirbyReporter\Report\ReportTemplateParser;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ReportTemplateParserTest extends MockeryTestCase
{
    private array $formData = [
        'title' => 'Lorem',
        'formFields' => [
            'description' => 'Test',
        ],
    ];

    public function test_parse_reporter_template(): void
    {
        $mock = Mockery::mock('alias:'.Tpl::class);
        $mock->shouldReceive('load')->andReturn('my-loaded-template');

        $formData = new FormData($this->formData);
        $parser = $this->getObjectForTrait(ReportTemplateParser::class);
        $parsedTemplate = $parser->parseTemplate($formData);

        $this->assertTrue(is_string($parsedTemplate));
        $this->assertEquals('my-loaded-template', $parsedTemplate);
    }
}
