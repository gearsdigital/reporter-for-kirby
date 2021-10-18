<?php

namespace KirbyReporter\Client;

use KirbyReporter\Mixins\ArrayTransformator;
use KirbyReporter\Mixins\Expander;
use KirbyReporter\Mixins\Request;
use QL\UriTemplate\Exception;

class Client
{
    use Expander;
    use Request;
    use ArrayTransformator;

    /**
     * Vendor.
     *
     * @var CreateVendor|null
     */
    private $vendor = null;

    /**
     * A templated url.
     *
     * @var null |string
     */
    private $issueUrl = null;

    /**
     * Personal access token.
     *
     * @var string | null
     */
    private $accessToken = null;

    protected function __construct(CreateVendor $vendor, $accessToken, $urlTemplate)
    {
        $this->vendor = $vendor;
        $this->setUrl($urlTemplate);
        $this->setToken($accessToken);
    }

    /**
     * Expanded url template.
     *
     * @param string $url
     *
     * @throws Exception
     */
    private function setUrl($url): void
    {
        $this->issueUrl = $this->expandUrl(
            $url,
            [
                'user' => $this->vendor->owner,
                'repo' => $this->vendor->repository,
            ]
        );
    }

    private function setToken($accessToken): void
    {
        if (is_null($accessToken)) {
            throw new Exception('reporter.form.error.optionNotFound.token');
        }
        $this->accessToken = $accessToken;
    }

    /**
     * Allow children to get the issue url (template).
     *
     * @return string|null
     */
    protected function getIssueUrlTemplate()
    {
        return $this->issueUrl;
    }

    /**
     * Allow children to get the access token.
     *
     * @return null|string
     */
    protected function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Return detected user.
     *
     * @return string|null
     */
    protected function getUser()
    {
        return $this->vendor->owner;
    }
}
