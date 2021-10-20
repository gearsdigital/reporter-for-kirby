<?php

namespace KirbyReporter\Client;

use KirbyReporter\Mixins\Expander;
use KirbyReporter\Mixins\Request;
use KirbyReporter\Report\ReportInterface;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Vendor\Vendor;

class GitlabReport implements ReportInterface
{
    use Request;
    use Expander;

    private string $urlTemplate = "https://gitlab.com/api/v4/projects/{user}%2F{repo}/issues";

    private Vendor $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public final function report(array $requestBody): ReportResponse
    {
        $url = $this->expandUrl($this->urlTemplate, [
            "user" => $this->vendor->owner,
            "repo" => $this->vendor->repository,
        ]);

        $requestBody = [
            "title" => $requestBody['title'],
            "description" => $requestBody['description'],
        ];

        $header = ["Private-Token" => $this->vendor->token];
        $request = $this->post($url, $requestBody, $header);
        $body = json_decode($request->getBody()->getContents(), true);

        $status = $request->getStatusCode();
        $issueId = $body['iid'];
        $issueUrl = $body['web_url'];

        return new ReportResponse($status, $issueUrl, $issueId);
    }
}
