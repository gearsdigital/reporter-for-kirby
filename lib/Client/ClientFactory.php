<?php

namespace KirbyReporter\Client;

use QL\UriTemplate\Exception;

/**
 * Create a Client.
 *
 * @package KirbyReporter
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ClientFactory
{
    public $vendor;

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function __construct($url, $token, $user)
    {
        $vendor = new VendorFactory($url);

        switch ($vendor->name) {
            case 'github':
                $this->vendor = new Github($vendor, $token);
                break;
            case 'gitlab':
                $this->vendor = new Gitlab($vendor, $token);
                break;
            case 'bitbucket':
                $this->vendor = new BitbucketCloud($vendor, $token, $user);
                break;
        }
    }
}
