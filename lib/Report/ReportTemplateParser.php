<?php

namespace KirbyReporter\Report;

use Kirby\Toolkit\Tpl;
use KirbyReporter\Model\FormData;

trait ReportTemplateParser
{
    private string $pluginName = 'gearsdigital/reporter-for-kirby';

    public function parseTemplate(FormData $formData): string
    {
        return Tpl::load($this->getReportTemplate(), ['title' => $formData->getTitle(), 'fields' => $formData->getFormFields()]);
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
