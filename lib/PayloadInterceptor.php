<?php

namespace KirbyReporter\Client;

use Kirby\Toolkit\Tpl;

class PayloadInterceptor
{
    private $template = 'reporter.php';
    private $pluginName = 'gearsdigital/kirby-reporter';
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function get()
    {
        return [
            'title'       => $this->payload['title'],
            'description' => $this->renderIssueTemplate($this->payload['formFields']),
        ];
    }

    private function renderIssueTemplate($formFields)
    {
        return Tpl::load($this->getTemplate(), ['form' => $formFields]);
    }

    private function getTemplate()
    {
        $templateRoot = kirby()->root('templates');
        $pluginRoot = kirby()->plugin($this->pluginName);
        $templatePath = file_exists($templateRoot.DS.$this->template)
            ? $templateRoot
            : $pluginRoot->root().DS."templates";

        return $templatePath.DS.$this->template;
    }
}
