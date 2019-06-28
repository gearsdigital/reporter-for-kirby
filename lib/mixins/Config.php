<?php

namespace KirbyReporter\Mixins;

/**
 * This is the basic configuration.
 *
 * @package KirbyReporter
 */
trait Config
{
    /**
     * Currently supported platforms.
     *
     * Define all supported platforms like Github or
     * Gitlab without TLD.
     */
    public $providers
        = [
            'github',
            'gitlab',
            'bitbucket'
        ];
}
