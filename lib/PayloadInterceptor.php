<?php

namespace KirbyReporter\Client;

use Kirby\Toolkit\Tpl;

class PayloadInterceptor
{
    private $template = 'reporter.php';
    private $pluginName = 'gearsdigital/kirby-reporter';
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function get(): array
    {
        return [
            'title'       => (string)$this->payload['title'],
            'description' => (string)$this->renderIssueTemplate(),
        ];
    }

    public function renderIssueTemplate(): string
    {
        return Tpl::load(
            $this->getTemplate(),
            ['fields' => $this->payload['formFields']]
        );
    }

    private function getTemplate(): string
    {
        $templateRoot = kirby()->root('templates');
        $pluginRoot = kirby()->plugin($this->pluginName);
        $templatePath = file_exists($templateRoot.DS.$this->template)
            ? $templateRoot
            : $pluginRoot->root().DS."templates";

        return $templatePath.DS.$this->template;
    }
}
