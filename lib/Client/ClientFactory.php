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
     */
    public function __construct(VendorFactory $vendor, $token, $user)
    {
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
