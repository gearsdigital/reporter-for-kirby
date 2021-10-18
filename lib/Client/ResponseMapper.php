<?php

namespace KirbyReporter\Client;

/**
 * ResponseMapper

 *
 * @package KirbyReporter\Client
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ResponseMapper
{

    public int $status;

    public string $issueId;

    public string $issueUrl;

    public function __construct($issueUrl, $issueId, $status)
    {
        $this->setStatus($status);
        $this->setIssueUrl($issueUrl);
        $this->setIssueId($issueId);
    }

    public function setIssueId($id)
    {
        $this->issueId = $id;
    }

    public function setIssueUrl($url)
    {
        $this->issueUrl = $url;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

}
