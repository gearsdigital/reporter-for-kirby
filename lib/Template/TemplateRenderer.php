<?php

namespace KirbyReporter\Template;

use Kirby\Toolkit\Tpl;

/**
 * Loads and renders the reporter template.
 *
 * @package KirbyReporter\Payload
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class TemplateRenderer
{
    private string $pluginName = 'gearsdigital/kirby-reporter';

    public function renderReportTemplate(array $formData): string
    {
        return Tpl::load($this->getReportTemplate(), ['fields' => $formData['formFields']]);
    }

    private function getReportTemplate(): string
    {
        $template = 'reporter.php';
        $templateRoot = kirby()->root('templates');
        $pluginRoot = kirby()->plugin($this->pluginName);
        $templatePath = file_exists($templateRoot.DS.$template) ? $templateRoot : $pluginRoot->root().DS."templates";

        return $templatePath.DS.$template;
    }
}
