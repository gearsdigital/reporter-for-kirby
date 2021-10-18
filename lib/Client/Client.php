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

    private ?CreateVendor $vendor = null;

    private ?string $issueUrl = null;

    private ?string $accessToken = null;

    private ?string $user = null;

    /**
     * @throws Exception
     */
    protected function __construct(CreateVendor $vendor, $accessToken, $urlTemplate, $user)
    {
        $this->vendor = $vendor;
        $this->setUrl($urlTemplate);
        $this->setToken($accessToken);

        if ($user) {
            $this->user = $user;
        }
    }

    /**
     * Expanded url template.
     *
     * @param  string  $url
     *
     * @throws Exception
     */
    private function setUrl(string $url): void
    {
        $this->issueUrl = $this->expandUrl(
            $url,
            [
                'user' => $this->vendor->owner,
                'repo' => $this->vendor->repository,
            ]
        );
    }

    /**
     * @throws Exception
     */
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
    protected function getIssueUrlTemplate(): ?string
    {
        return $this->issueUrl;
    }

    /**
     * Allow children to get the access token.
     *
     * @return null|string
     */
    protected function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Return detected or specified user.
     *
     * @return string|null
     */
    protected function getUser(): ?string
    {
        if ($this->user != null) {
            return $this->user;
        }

        return $this->vendor->owner;
    }
}
