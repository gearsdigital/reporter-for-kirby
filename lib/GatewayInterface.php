<?php

namespace Gearsdigital\KirbyReporter;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface GatewayInterface
 *
 * @package Gearsdigital\KirbyReporter
 */
interface GatewayInterface
{
    /**
     * GatewayInterface constructor.
     *
     * @param $owner
     * @param $repository
     * @param $accessToken
     */
    public function __construct(string $owner, string $repository, string $accessToken);

    /**
     * @param  string  $requestBody  Any valid JSON string
     *
     * @return array
     */
    public function createIssue(array $requestBody);

    /**
     *
     * @param  ResponseInterface  $response
     *
     * @return Response
     */
    public function createResponse(ResponseInterface $response): Response;
}
