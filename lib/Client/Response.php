<?php

namespace KirbyReporter\Client;

use Psr\Http\Message\ResponseInterface;

/**
 * Represents a response object.
 *
 * @package KirbyReporter\Client
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class Response
{
    /**
     * @var int
     */
    public $status;

    /**
     * @var array
     */
    public $body;

    public function __construct(ResponseInterface $response)
    {
        $this->status = $response->getStatusCode();
        $this->body = json_decode($response->getBody()->getContents(), true);
    }
}
