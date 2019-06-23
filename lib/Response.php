<?php

namespace Gearsdigital\KirbyReporter;

/**
 * Class Response
 *
 * @package Gearsdigital\KirbyReporter
 */
class Response
{
    public $status;
    public $platform;
    public $repo;
    public $owner;
    public $issueUrl;
    public $issueId;

    /**
     * @param  string  $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @param  string  $platform
     */
    public function setPlatform($platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @param  string  $repo
     */
    public function setRepo($repo): void
    {
        $this->repo = $repo;
    }

    /**
     * @param  string  $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @param  string  $issueUrl
     */
    public function setIssueUrl($issueUrl): void
    {
        $this->issueUrl = $issueUrl;
    }

    /**
     * @param  string  $issueId
     */
    public function setIssueId($issueId, $prefix = '#'): void
    {
        $this->issueId = $prefix . $issueId;
    }
}
