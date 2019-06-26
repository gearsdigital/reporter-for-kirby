<?php

namespace KirbyReporter\Client;

class ResponseModel
{

    public $status;
    public $rissueId;
    public $issueUrl;

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function setIssueId($rissueId): void
    {
        $this->rissueId = $rissueId;
    }

    public function setIssueUrl($issueUrl): void
    {
        $this->issueUrl = $issueUrl;
    }

}
