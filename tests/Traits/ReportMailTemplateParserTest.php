<?php

namespace KirbyReporter\Traits;

use Kirby\Toolkit\Tpl;
use KirbyReporter\Model\FormData;
use KirbyReporter\Report\ReportMailTemplateParser;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ReportMailTemplateParserTest extends MockeryTestCase
{

    private array $formData = [
        'title' => 'Lorem',
        'formFields' => [
            'description' => 'Test',
        ],
    ];

    public function test_parse_report_mail_text_template(): void
    {
        $mock = Mockery::mock('alias:'.Tpl::class);
        $mock->shouldReceive('load')->andReturn('my-loaded-text-template');

        $formData = new FormData($this->formData);
        $parser = $this->getObjectForTrait(ReportMailTemplateParser::class);
        $parsedTemplate = $parser->parseTemplate($formData, 'text');

        $this->assertTrue(is_string($parsedTemplate));
        $this->assertEquals('my-loaded-text-template', $parsedTemplate);
    }

    public function test_parse_report_mail_html_template(): void
    {
        $mock = Mockery::mock('alias:'.Tpl::class);
        $mock->shouldReceive('load')->andReturn('my-loaded-html-template');

        $formData = new FormData($this->formData);
        $parser = $this->getObjectForTrait(ReportMailTemplateParser::class);
        $parsedTemplate = $parser->parseTemplate($formData, 'html');

        $this->assertTrue(is_string($parsedTemplate));
        $this->assertEquals('my-loaded-html-template', $parsedTemplate);
    }

}
