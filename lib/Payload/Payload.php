<?php

namespace KirbyReporter\Payload;

use Kirby\Toolkit\Tpl;

/**
 * Defines the request payload with rendered template.
 *
 * @package KirbyReporter\Payload
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class Payload
{
    private string $template = 'reporter.php';
    private string $pluginName = 'gearsdigital/kirby-reporter';
    public array $payload;

    public function __construct(array $formData)
    {
        $this->payload = [
            'title' => $formData['title'],
            'description' => $this->renderReportTemplate($formData),
        ];
    }

    private function renderReportTemplate(array $formData): string
    {
        return Tpl::load($this->getReportTemplate(), ['fields' => $formData['formFields']]);
    }

    private function getReportTemplate(): string
    {
        $templateRoot = kirby()->root('templates');
        $pluginRoot = kirby()->plugin($this->pluginName);
        $templatePath = file_exists($templateRoot.DS.$this->template) ? $templateRoot : $pluginRoot->root().DS."templates";

        return $templatePath.DS.$this->template;
    }
}
