<?php


namespace Gearsdigital\KirbyReporter;

/**
 * Class ResponseError
 *
 * @package Gearsdigital\KirbyReporter
 */
class ResponseError
{
    public $status;
    public $platform;
    public $repo;

    /**
     * @param  mixed  $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @param  mixed  $platform
     */
    public function setPlatform($platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @param  mixed  $repo
     */
    public function setRepo($repo): void
    {
        $this->repo = $repo;
    }
}
