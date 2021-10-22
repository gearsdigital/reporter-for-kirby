<?php

namespace KirbyReporter\Client;

use KirbyReporter\Report\ReportInterface;
use KirbyReporter\Report\ReportTemplateParser;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Traits\Expander;
use KirbyReporter\Traits\Request;
use KirbyReporter\Vendor\Vendor;

class GitlabReport implements ReportInterface
{
    use Request;
    use Expander;
    use ReportTemplateParser;

    private string $urlTemplate = "https://gitlab.com/api/v4/projects/{user}%2F{repo}/issues";

    private Vendor $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public final function report(array $reportData): ReportResponse
    {
        $url = $this->expandUrl($this->urlTemplate, [
            "user" => $this->vendor->owner,
            "repo" => $this->vendor->repository,
        ]);

        $reportData = [
            "title" => $reportData['title'],
            "description" => $this->parseTemplate($reportData),
        ];

        $header = ["Private-Token" => $this->vendor->token];
        $request = $this->post($url, $reportData, $header);
        $body = json_decode($request->getBody()->getContents(), true);

        $status = $request->getStatusCode();
        $issueId = $body['iid'];
        $issueUrl = $body['web_url'];

        return new ReportResponse($status, $issueUrl, $issueId);
    }
}
