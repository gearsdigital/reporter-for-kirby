<?php

namespace KirbyReporter\Vendor;

use Exception;

/**
 *
 * @package KirbyReporter
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class Vendor
{
    use Vendors;

    public string $url;

    public string $name;

    public string $owner;

    public string $user;

    public string $token;

    public string $repository;

    public function __construct(string $url, string $token, ?string $user = null)
    {
        $this->url = $url;
        $this->token = $token;
        $this->name = $this->extractProviderName();
        $this->owner = $this->getPathSegment();
        $this->repository = $this->getPathSegment(1);

        $this->setUser($user);

        if (!$this->isSupportedPlatform()) {
            throw new Exception('reporter.form.error.platform.unsupported', 501);
        }
    }

    private function setUser(?string $user): void
    {
        $this->user = $user == null ? $this->owner : $user;
    }

    private function extractProviderName(): ?string
    {
        $hostname = parse_url($this->url, PHP_URL_HOST);
        if (preg_match(
            '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i',
            $hostname,
            $regs
        )
        ) {
            return strtok($regs['domain'], '.');
        }

        return null;
    }

    private function getPathSegment(int $positon = 0): string
    {
        $urlPath = parse_url($this->url, PHP_URL_PATH);
        $pathArray = explode('/', ltrim($urlPath, '/'));
        $fragment = $pathArray[$positon];
        if (is_null($fragment)) {
            return $this->url;
        }

        return $fragment;
    }

    private function isSupportedPlatform(): bool
    {
        return in_array($this->name, $this->providers);
    }

}
