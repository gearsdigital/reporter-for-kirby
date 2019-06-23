<?php

namespace Gearsdigital\KirbyReporter;

/**
 * Class Gateway
 *
 * @package Gearsdigital\KirbyReporter
 */
class Gateway
{
    public $api = null;

    public function __construct(Platform $provider)
    {
        switch ($provider->platform) {
            case 'github':
                $this->api = new Github($provider->owner, $provider->repository, $provider->accessToken);
                break;
            case 'gitlab':
                $this->api = new Gitlab($provider->owner, $provider->repository, $provider->accessToken);
        }
    }
}
