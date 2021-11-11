<?php

namespace KirbyReporter\Report;

use Kirby\Toolkit\Tpl;
use KirbyReporter\Model\FormData;

trait ReportMailTemplateParser
{
    private string $pluginName = 'gearsdigital/reporter-for-kirby';

    public function parseTemplate(FormData $formData, string $type): string
    {
        return Tpl::load($this->getReportTemplate($type), ['title' => $formData->getTitle(), 'fields' => $formData->getFormFields()]);
    }

    private function getReportTemplate(string $type): string
    {
        $template = "report.${type}.php";
        $templateRoot = kirby()->root('templates').DS."emails";
        $pluginRoot = kirby()->plugin($this->pluginName);
        $templatePath = file_exists($templateRoot.DS.$template) ? $templateRoot : $pluginRoot->root().DS."templates/emails";

        return $templatePath.DS."$template";
    }
}
