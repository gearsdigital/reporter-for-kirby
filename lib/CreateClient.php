<?php

namespace KirbyReporter\Client;

/**
 * Create a Client.
 *
 * @package KirbyReporter
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class CreateClient
{
    public $api;

    public function __construct(CreateVendor $vendor, $token)
    {
        switch ($vendor->name) {
            case 'github':
                $this->api = new Github($vendor, $token);
                break;
            case 'gitlab':
                $this->api = new Gitlab($vendor, $token);
                break;
        }
    }
}
