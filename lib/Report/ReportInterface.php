<?php

namespace KirbyReporter\Report;

/**
 * @package KirbyReporter\Report
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
interface ReportInterface
{
    public function report(array $requestBody): ReportResponse;
}
