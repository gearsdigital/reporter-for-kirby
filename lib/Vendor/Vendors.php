<?php

namespace KirbyReporter\Vendor;

trait Vendors
{
    /**
     * Currently supported platforms.
     *
     * Define all supported platforms like Github or
     * Gitlab without TLD.
     */
    public array $providers = [
        'github',
        'gitlab',
        'bitbucket',
    ];
}
