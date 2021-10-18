<?php

namespace KirbyReporter\Client;

use QL\UriTemplate\Exception;

/**
 * Create a Client.
 *
 * @package KirbyReporter
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class CreateClient
{
    public $api;

    /**
     * @throws Exception
     */
    public function __construct(CreateVendor $vendor, $token, $user)
    {
        switch ($vendor->name) {
            case 'github':
                $this->api = new Github($vendor, $token);
                break;
            case 'gitlab':
                $this->api = new Gitlab($vendor, $token);
                break;
            case 'bitbucket':
                $this->api = new BitbucketCloud($vendor, $token, $user);
                break;
        }
    }
}
