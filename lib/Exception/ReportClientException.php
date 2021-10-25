<?php

namespace KirbyReporter\Exception;

use Throwable;

class ReportClientException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ($code == 400) {
            $this->setMessage('reporter.form.error.badRequest');
        } elseif ($code == 401 || $code == 403) {
            $this->setMessage('reporter.form.error.authFailed');
        } elseif ($code == 404) {
            $this->setMessage('reporter.form.error.repoNotFound');
        } elseif($code == 0 || $code > 404) {
            $this->setMessage('reporter.form.error.apiCommunicationError');
        }
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
