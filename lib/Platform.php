<?php

namespace Gearsdigital\KirbyReporter;

use Exception;

/**
 * Class Platform
 *
 * Creates an object containung the platform, user, repository and acccess token.
 * It expects a URL in the following format: https://platform.com/user/repository/
 *
 * @package Gearsdigital\KirbyReporter
 */
class Platform
{
    private $url;
    public $accessToken;
    public $platform;
    public $supported = false;
    public $owner;
    public $repository;

    public function __construct($url, $accessToken)
    {
        $this->setUrl($url);
        $this->setAccessToken($accessToken);
        $this->setPlatform($this->getPlatform());
        $this->setSupported($this->isSupportedPlatform());
        $this->setOwner($this->getOwner());
        $this->setRepository($this->getRepository());
    }

    /**
     * Explodes a URL into path segments and returns one.
     *
     * @param  string  $url
     * @param  int  $positon  Array postion
     *
     * @return mixed
     */
    private function getPathSegment($url, $positon = 0)
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        $pathArray = explode('/', ltrim($urlPath, '/'));

        return $pathArray[$positon];
    }

    /**
     * Returns the domain name without TLD or subdomains.
     *
     * @return string
     * @example
     * https://github.com -> github
     * https://api.github.com -> github
     * https://api.github:8080.com -> github
     */
    private function getPlatform()
    {
        $hostname = parse_url($this->url, PHP_URL_HOST);

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $hostname, $regs)) {
            return strtok($regs['domain'], '.');
        }

        return $this->url;
    }

    private function getOwner()
    {
        return $this->getPathSegment($this->url);
    }

    private function getRepository()
    {
        return $this->getPathSegment($this->url, 1);
    }

    private function isSupportedPlatform()
    {
        return in_array($this->platform, Config::$platforms);
    }

    private function setAccessToken($token)
    {
        if (is_null($token)) {
            throw new Exception('reporter.form.error.optionNotFound.token');
        }
        $this->accessToken = $token;
    }

    private function setUrl($url)
    {
        if (is_null($url)) {
            throw new Exception('reporter.form.error.optionNotFound.url');
        }
        $this->url = $url;
    }

    private function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    private function setSupported(bool $supported): void
    {
        if (!$supported) {
            throw new Exception('reporter.form.error.platform.unsupported');
        }
        $this->supported = $supported;
    }

    private function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    private function setRepository($repository): void
    {
        $this->repository = $repository;
    }
}
