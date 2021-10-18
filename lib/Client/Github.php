<?php

namespace KirbyReporter\Client;

/**
 * Github API.
 *
 * @package KirbyReporter
 * @author  Steffen Giers <steffen.giers@gmail.com>
 */
class Github extends Client implements ClientInterface
{
    private string $urlTemplate = "https://api.github.com/repos/{user}/{repo}/issues";

    public function __construct(CreateVendor $vendor, $accessToken)
    {
        parent::__construct($vendor, $accessToken, $this->urlTemplate);
    }

    public function createIssue(array $requestBody): ResponseMapper
    {
        $mapper = new RequestDataMapper($requestBody, [
            'title' => 'title',
            'description' => 'body',
        ]);

        $request = $this->post($this->getIssueUrlTemplate(), $mapper->getMappedData(), [
            "Authorization" => "token ".$this->getAccessToken(),
        ]);

        $response = new Response($request);
        $url = $response->body['html_url'];
        $id = $response->body['number'];
        $status = $response->status;

        return new ResponseMapper($url, $id, $status);
    }
}
