<?php

namespace KirbyReporter\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use KirbyReporter\Exception\ReportClientException;
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

    /** @throws ReportClientException */
    public final function post(string $url, array $body, array $headers): ResponseInterface
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        $headers['Content-Type'] = 'application/json';

        try {
            return $this->client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
        } catch (GuzzleException $e) {
            throw new ReportClientException($e->getMessage(), $e->getCode());
        }
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
