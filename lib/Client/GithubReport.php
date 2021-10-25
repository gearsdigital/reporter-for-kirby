<?php

namespace KirbyReporter\Client;

use KirbyReporter\Model\FormData;
use KirbyReporter\Report\ReportTemplateParser;
use KirbyReporter\Traits\Expander;
use KirbyReporter\Traits\Request;
use KirbyReporter\Report\ReportInterface;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Vendor\Vendor;

class GithubReport implements ReportInterface
{
    use Request;
    use Expander;
    use ReportTemplateParser;

    private string $urlTemplate = "https://api.github.com/repos/{user}/{repo}/issues";

    private Vendor $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public final function report(FormData $reportData): ReportResponse
    {
        $url = $this->expandUrl($this->urlTemplate, [
            "user" => $this->vendor->owner,
            "repo" => $this->vendor->repository,
        ]);

        $reportData = [
            "title" => $reportData->getTitle(),
            "body" =>  $this->parseTemplate($reportData->getFormFields()),
        ];

        $header = ["Authorization" => "token ".$this->vendor->token];
        $request = $this->post($url, $reportData, $header);
        $body = json_decode($request->getBody()->getContents(), true);

        $status = $request->getStatusCode();
        $issueId = $body['number'];
        $issueUrl = $body['html_url'];

        return new ReportResponse($status, $issueUrl, $issueId);
    }
}
