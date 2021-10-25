<?php

namespace KirbyReporter\Report;

use KirbyReporter\Model\FormData;

/**
 * @package KirbyReporter\Report
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
interface ReportInterface
{
    public function report(FormData $reportData): ReportResponse;
}
