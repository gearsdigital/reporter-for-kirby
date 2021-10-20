<?php

namespace KirbyReporter\Client;

use KirbyReporter\Mixins\Expander;
use KirbyReporter\Mixins\Request;
use KirbyReporter\Report\ReportInterface;
use KirbyReporter\Report\ReportResponse;
use KirbyReporter\Vendor\Vendor;

class BitbucketReport implements ReportInterface
{
    use Request;
    use Expander;

    private string $urlTemplate = "https://api.bitbucket.org/2.0/repositories/{user}/{repo}/issues";

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

        $header = ["Authorization" => "Basic ".$this->getBasicAuth()];
        $request = $this->post($url, $requestBody, $header);
        $body = json_decode($request->getBody()->getContents(), true);

        $status = $request->getStatusCode();
        $issueId = $body['id'];
        $issueUrl = $body['repository']['links']['html']['href'].'/issues/'.$issueId;

        return new ReportResponse($status, $issueUrl, $issueId);
    }

    private function getBasicAuth(): string
    {
        return base64_encode($this->vendor->user.':'.$this->vendor->token);
    }
}
