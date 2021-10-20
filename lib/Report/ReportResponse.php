<?php

namespace KirbyReporter\Report;

/**
 * Defines a report response object.
 *
 * @package KirbyReporter\Report
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ReportResponse
{
    public int $status;
    public string $issueId;
    public string $issueUrl;

    public function __construct(int $status, string $issueUrl, string $issueId)
    {
        $this->setStatus($status);
        $this->setIssueUrl($issueUrl);
        $this->setIssueId($issueId);
    }

    public final function toJson(): string
    {
        return json_encode([
            "status" => $this->status,
            "issueUrl" => $this->issueUrl,
            "issueId" => $this->issueId,
        ]);
    }

    private function setIssueId(string $id): void
    {
        $this->issueId = $id;
    }

    private function setIssueUrl(string $url): void
    {
        $this->issueUrl = $url;
    }

    private function setStatus(int $status): void
    {
        $this->status = $status;
    }

}
