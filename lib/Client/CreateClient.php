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
    public Gitlab|Github|BitbucketCloud $api;

    public function __construct(CreateVendor $vendor, $token)
    {
        $this->api = match ($vendor->name) {
            'github' => new Github($vendor, $token),
            'gitlab' => new Gitlab($vendor, $token),
            'bitbucket' => new BitbucketCloud($vendor, $token),
        };
    }
}
