<?php

namespace KirbyReporter\Client;

use KirbyReporter\Model\FormData;
use KirbyReporter\Report\ReportInterface;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Traits\Expander;
use KirbyReporter\Traits\Request;
use KirbyReporter\Vendor\IssueTracker;

class GithubReport implements ReportInterface
{
    use Request;
    use Expander;

    private string $urlTemplate = "https://api.github.com/repos/{user}/{repo}/issues";

    private IssueTracker $vendor;

    public function __construct(IssueTracker $vendor)
    {
        $this->vendor = $vendor;
    }

    public final function report(FormData $reportData, ?string $templateData): ReportResponse
    {
        $url = $this->expandUrl($this->urlTemplate, [
            "user" => $this->vendor->getOwner(),
            "repo" => $this->vendor->getRepository(),
        ]);

        $reportData = [
            "title" => $reportData->getTitle(),
            "body" => $templateData,
        ];

        $header = ["Authorization" => "token ".$this->vendor->getToken()];
        $request = $this->post($url, $reportData, $header);
        $body = json_decode($request->getBody()->getContents(), true);

        $status = $request->getStatusCode();
        $issueId = $body['number'];
        $issueUrl = $body['html_url'];

        return new ReportResponse($status, $issueUrl, $issueId);
    }
}
