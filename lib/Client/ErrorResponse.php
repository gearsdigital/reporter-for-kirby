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
    public $message;

    public function __construct(Exception $exception)
    {
        $this->setStatus($exception->getCode());
        $this->setMessage($exception->getMessage());
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
