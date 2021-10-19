<?php

namespace KirbyReporter\Client;

/**
 * BitbucketCloud API.
 *
 * @package KirbyReporter
 * @author  Steffen Giers <steffen.giers@gmail.com>
 */
class BitbucketCloud extends Client implements ClientInterface
{
    private string $urlTemplate = "https://api.bitbucket.org/2.0/repositories/{user}/{repo}/issues";

    public function __construct(VendorFactory $vendor, $accessToken, $user = null)
    {
        parent::__construct($vendor, $accessToken, $this->urlTemplate, $user);
    }

    public function createIssue(array $requestBody): ResponseMapper
    {
        $mapper = new RequestDataMapper($requestBody, [
            'description' => ['content.raw'],
        ]);
        $request = $this->post($this->getIssueUrlTemplate(), $mapper->getMappedData(), [
            "Authorization" => "Basic ".$this->getBasicAuth(),
        ]);

        $body = json_decode($request->getBody()->getContents(), true);
        $id = $body['id'];
        $url = $body['repository']['links']['html']['href'].'/issues/'.$id;
        $status = $request->getStatusCode();

        return new ResponseMapper($url, $id, $status);
    }

    private function getBasicAuth(): string
    {
        return base64_encode($this->getUser().':'.$this->getAccessToken());
    }
}
