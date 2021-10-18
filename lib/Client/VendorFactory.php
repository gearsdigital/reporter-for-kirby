<?php

namespace KirbyReporter\Client;

use Exception;
use KirbyReporter\Mixins\Config;

/**
 * Detect a Vendor by given Url.
 *
 * This class parses the given Url and extracts the provider name, the repository
 * owner and the repository name.
 *
 * It will throw an exeption if the detected vendor isn't supported or the
 * Url is invalid.
 *
 * @package KirbyReporter
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class VendorFactory
{

    use Config;

    /**
     * This is root url from which all other properties
     * are extracted.
     *
     * Must be this format: https://vender.com/owner/repository
     *
     * @var null | string
     */
    public $url = null;

    /**
     * Resolved Provider.
     *
     * @var null|string
     */
    public $name = null;

    /**
     * Resolved repository owner.
     *
     * @var null|string
     */
    public $owner = null;

    /**
     * Resolved repository.
     *
     * @var null|string
     */
    public $repository = null;

    /**
     * Vendor constructor.
     *
     * @param string $url
     *
     * @throws Exception reporter.form.error.platform.unsupported
     */
    public function __construct($url)
    {
        $this->setUrl($url);
        $this->name = $this->extractProviderName();
        $this->owner = $this->getPathSegment();
        $this->repository = $this->getPathSegment(1);
        if (!$this->isSupportedPlatform()) {
            throw new Exception('reporter.form.error.platform.unsupported', 501);
        }
    }

    private function setUrl($url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('reporter.form.error.optionNotFound.url');
        }
        $this->url = $url;
    }

    private function extractProviderName()
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
