<?php

namespace KirbyReporter\Mixins;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Perform a HTTP request.
 *
 * @package KirbyReporter\Mixins
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
trait Request
{
    public final function post(string $url, array $body, array $headers): ResponseInterface
    {
        $headers['Content-Type'] = 'application/json';
        $client = new Client();

        return $client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);
    }
}
