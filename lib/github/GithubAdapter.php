<?php

namespace Gearsdigital\KirbyReporter;

/**
 * Class GithubAdapter
 *
 * @package Gearsdigital\KirbyReporter
 */
class GithubAdapter
{
    public $body;
    public $title;

    public function __construct(array $requestBody)
    {
        $this->setTitle($requestBody['title']);
        $this->setBody($requestBody['description']);
    }

    /**
     * @param  mixed  $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @param  mixed  $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

}
