<?php

namespace Gearsdigital\KirbyReporter;

/**
 * Class Config
 *
 * @package Gearsdigital\KirbyReporter
 */
class Config
{
    /**
     * Supported platforms.
     *
     * Define all supported platforms like Github or
     * Gitlab without TLD.
     *
     * @var array
     */
    public static $platforms = [
        'github',
        'gitlab'
    ];
}
