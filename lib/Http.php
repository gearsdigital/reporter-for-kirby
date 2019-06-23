<?php

namespace Gearsdigital\KirbyReporter;

use GuzzleHttp\Client;

/**
 * Class Http
 *
 * @package Gearsdigital\KirbyReporter
 */
class Http
{

    private $http;

    public function __construct()
    {
        $this->http = new Client();
    }

    public function post($url, $body, $headers = [])
    {
        return $this->http->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);
    }

}
