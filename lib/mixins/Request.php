<?php

namespace KirbyReporter\Mixins;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Request handler.
 *
 * @package KirbyReporter\Helper
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
trait Request
{
    /**
     * Make a post request to given $url;
     *
     * @param  string  $url
     * @param $body
     * @param $headers
     *
     * @return ResponseInterface
     */
    public function post($url, $body, $headers): ResponseInterface
    {
        // it might make sense to refactor this if neccessary
        $client = new Client();
        $headers['Content-Type'] = 'application/json';

        return $client->post(
            $url,
            [
                'headers' => $headers,
                'json'    => $body,
            ]
        );
    }
}
