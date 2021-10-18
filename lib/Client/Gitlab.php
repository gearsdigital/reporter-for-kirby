<?php

namespace KirbyReporter\Client;

/**
 * Gitlab API.
 *
 * @package KirbyReporter
 * @author  Steffen Giers <steffen.giers@gmail.com>
 */
class Gitlab extends Client implements ClientInterface
{
    private string $urlTemplate = "https://gitlab.com/api/v4/projects/{user}%2F{repo}/issues";

    public function __construct(CreateVendor $vendor, $accessToken)
    {
        parent::__construct($vendor, $accessToken, $this->urlTemplate);
    }

    public function createIssue(array $requestBody): ResponseMapper
    {
        $request = $this->post(
            $this->getIssueUrlTemplate(),
            $requestBody,
            [
                "Private-Token" => $this->getAccessToken(),
            ]
        );

        $response = new Response($request);
        $url = $response->body['web_url'];
        $id = $response->body['iid'];
        $status = $response->status;

        return new ResponseMapper($url, $id, $status);
    }
}
