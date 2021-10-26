<?php

namespace KirbyReporter\Vendor;

use KirbyReporter\Exception\OptionNotFoundException;
use KirbyReporter\Exception\UnsupportedPlatformException;

/**
 *
 * @package KirbyReporter
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class Vendor
{
    use Vendors;

    private string $url;

    private string $name;

    private string $token;

    private string $owner;

    private string $repository;

    private string $user;

    /**
     * @throws UnsupportedPlatformException|OptionNotFoundException
     */
    public function __construct(?string $url, ?string $token, ?string $user = null)
    {
        $this->setUrl($url);
        $this->setToken($token);
        $this->setName($this->extractProviderName());
        $this->setOwner($this->getPathSegment());
        $this->setRepository($this->getPathSegment(1));
        $this->setUser($user);

        if (!$this->isSupportedPlatform()) {
            throw new UnsupportedPlatformException('reporter.form.error.platform.unsupported', 400);
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    private function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /** @throws OptionNotFoundException */
    private function setUrl(?string $url): void
    {
        if ($this->isDefined($url)) {
            throw new OptionNotFoundException('reporter.form.error.optionNotFound.url', 400);
        }

        $this->url = $url;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /** @throws OptionNotFoundException */
    private function setToken(?string $token): void
    {
        if ($this->isDefined($token)) {
            throw new OptionNotFoundException('reporter.form.error.optionNotFound.token', 400);
        }

        $this->token = $token;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    private function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    private function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    private function setUser(?string $user): void
    {
        $this->user = $user == null ? $this->getOwner() : $user;
    }

    public function getUser(): string
    {
        return $this->user;
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

    private function isDefined(?string $value): bool
    {
        if (empty($value)) {
            return true;
        }

        return false;
    }
}
