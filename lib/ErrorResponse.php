<?php

namespace KirbyReporter\Client;

use Exception;

/**
 * Class ErrorResponse
 *
 * @package KirbyReporter
 */
class ErrorResponse
{
    public $status;
    public $platform;
    public $repo;

    public function __construct(Exception $exception)
    {
        $this->setStatus($exception->getCode());
    }

    /**
     * @param  mixed  $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
