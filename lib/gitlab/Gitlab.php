<?php

namespace Gearsdigital\KirbyReporter;

use Psr\Http\Message\ResponseInterface;
use QL\UriTemplate\UriTemplate;

class Gitlab implements GatewayInterface
{
    private $urlTemplate = "https://gitlab.com/api/v4/projects/{user}%2F{repo}/issues";
    private $owner;
    private $repository;
    private $accessToken;

    /**
     * GatewayInterface constructor.
     *
     * @param $owner
     * @param $repository
     * @param $accessToken
     */
    public function __construct(string $owner, string $repository, string $accessToken)
    {
        $this->owner = $owner;
        $this->repository = $repository;
        $this->accessToken = $accessToken;
    }

    /**
     * @param  string  $requestBody  Any valid JSON string
     *
     * @return false|string
     */
    public function createIssue(array $requestBody)
    {
        $http = new Http();
        $response = $http->post($this->getIssueUrl(), $requestBody, ["Private-Token" => $this->accessToken]);

        return $this->createResponse($response);
    }

    private function getIssueUrl()
    {
        $tpl = new UriTemplate($this->urlTemplate);
        return $tpl->expand([
            'user' => $this->owner,
            'repo' => $this->repository
        ]);
    }

    /**
     *
     * @param  ResponseInterface  $response
     *
     * @return Response
     */
    public function createResponse(ResponseInterface $response): Response
    {
        $responseBody = json_decode($response->getBody()->getContents());

        $responseEntity = new Response();
        $responseEntity->setStatus($response->getStatusCode());
        $responseEntity->setIssueUrl($responseBody->web_url);
        $responseEntity->setIssueId($responseBody->iid);

        return $responseEntity;
    }
}
