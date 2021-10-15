<?php

namespace KirbyReporter\Client;

/**
 * BitbucketCloud API.
 *
 * @package KirbyReporter
 * @author  Steffen Giers <steffen.giers@gmail.com>
 */
class BitbucketCloud extends Client
{
    private $urlTemplate = "https://api.bitbucket.org/2.0/repositories/{user}/{repo}/issues";

    public function __construct(CreateVendor $vendor, $accessToken)
    {
        parent::__construct($vendor, $accessToken, $this->urlTemplate);
    }

    public function createIssue(array $requestBody)
    {
        $mapper = new RequestDataMapper(
            $requestBody,
            [
                'description' => ['content.raw'],
            ]
        );
        $response = $this->post(
            $this->getIssueUrl(),
            $mapper->getMappedData(),
            [
                "Authorization" => "Basic " . $this->getBasicAuth(),
            ]
        );
        $responseBody = new Response($response);
        $mapper = new ResponseMapper($responseBody, []);
        $mapper->setIssueId($responseBody->body['id']);
        $mapper->setissueUrl(
            $responseBody->body['repository']['links']['html']['href'] . '/issues/' . $responseBody->body['id']
        );

        return $mapper;
    }

    private function getBasicAuth()
    {
        return base64_encode($this->getUser() . ':' . $this->getAccessToken());
    }
}
