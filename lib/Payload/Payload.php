<?php

namespace KirbyReporter\Payload;

use KirbyReporter\Template\TemplateRenderer;

/**
 * Creates the request payload with a rendered template.
 *
 * @package KirbyReporter\Payload
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class Payload
{
    public array $payload;

    public function __construct(array $formData, TemplateRenderer $templateRenderer)
    {
        $this->payload = [
            'title' => $formData['title'],
            'description' => $templateRenderer->renderReportTemplate($formData),
        ];
    }

}
