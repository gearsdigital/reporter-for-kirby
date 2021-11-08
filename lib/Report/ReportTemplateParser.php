<?php

namespace KirbyReporter\Report;

use Kirby\Toolkit\Tpl;

trait ReportTemplateParser
{
    private string $pluginName = 'gearsdigital/reporter-for-kirby';

    public function parseTemplate(array $templateData): string
    {
        return Tpl::load($this->getReportTemplate(), ['fields' => $templateData]);
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
