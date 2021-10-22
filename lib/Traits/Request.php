<?php

namespace KirbyReporter\Traits;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Perform a HTTP request.
 *
 * @package KirbyReporter\Traits
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
trait Request
{
    private ?Client $client = null;

    public final function post(string $url, array $body, array $headers): ResponseInterface
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        $headers['Content-Type'] = 'application/json';

        return $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
