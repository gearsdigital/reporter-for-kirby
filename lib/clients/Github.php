<?php

namespace KirbyReporter\Client;

/**
 * Github API.
 *
 * @package KirbyReporter
 * @author  Steffen Giers <steffen.giers@gmail.com>
 */
class Github extends Client
{
    private $urlTemplate = "https://api.github.com/repos/{user}/{repo}/issues";

    public function __construct(CreateVendor $vendor, $accessToken)
    {
        parent::__construct($vendor, $accessToken, $this->urlTemplate);
    }

    public function createIssue(array $requestBody)
    {
        $mapper = new RequestDataMapper(
            $requestBody,
            [
                'title'       => 'title',
                'description' => 'body',
            ]
        );
        $response = $this->post(
            $this->getIssueUrl(),
            $mapper->getMappedData(),
            [
                "Authorization" => "token ".$this->getAccessToken(),
            ]
        );
        $responseMap = [
            'number'   => 'issueId',
            'html_url' => 'issueUrl',
        ];
        $responseBody = new Response($response);
        $mapper = new ResponseMapper($responseMap, $responseBody);

        return $mapper;
    }
}
