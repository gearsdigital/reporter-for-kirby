<?php

namespace KirbyReporter\Client;

use Exception;
use KirbyReporter\Mixins\ArrayTransformator;

/**
 * ResponseMapper
 *
 * Public properties define the response object. If you're going to extend the response
 * object you have to make sure to adapt all implented maps.
 *
 * @package KirbyReporter\Client
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ResponseMapper
{

    use ArrayTransformator;

    public int $status;

    public string $issueId;

    public string $issueUrl;

    public function __construct(Response $response, array $map)
    {
        $map = $this->transform($response->body, $map);

        $this->setStatus($response->status);
        $this->setIssueUrl($map['issueUrl']);
        $this->setIssueId($map['issueId']);
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
