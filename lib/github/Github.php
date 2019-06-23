<?php

namespace Gearsdigital\KirbyReporter;

use Psr\Http\Message\ResponseInterface;
use QL\UriTemplate\UriTemplate;

/**
 * Class Github
 *
 * @package Gearsdigital\KirbyReporter
 */
class Github implements GatewayInterface
{
    private $urlTemplate = "https://api.github.com/repos/{user}/{repo}/issues";
    private $owner;
    private $repository;
    private $accessToken;

    public function __construct(string $owner, string $repository, string $accessToken)
    {
        $this->owner = $owner;
        $this->repository = $repository;
        $this->accessToken = $accessToken;
    }

    public function createIssue(array $requestBody)
    {
        $http = new Http();
        $body = new GithubAdapter($requestBody);
        $response = $http->post($this->getIssueUrl(), $body, ["Authorization" => "token ".$this->accessToken]);

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
     * @param  ResponseInterface  $response
     *
     * @return Response
     */
    public function createResponse(ResponseInterface $response): Response
    {
        $responseBody = json_decode($response->getBody()->getContents());

        $responseEntity = new Response();
        $responseEntity->setStatus($response->getStatusCode());
        $responseEntity->setIssueUrl($responseBody->html_url);
        $responseEntity->setIssueId($responseBody->number);

        return $responseEntity;
    }
}
