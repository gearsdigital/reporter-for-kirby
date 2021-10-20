<?php

namespace KirbyReporter\Mixins;

use QL\UriTemplate\Exception;
use QL\UriTemplate\UriTemplate;

/**
 * Expander is a simple helper to expand templated Urls.
 *
 * @package KirbyReporter\Mixins
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
trait Expander
{
    /**
     * Utilizes RFC 6570 (Level 4) Url expansion.
     *
     * @param  string  $template
     * @param  array  $data
     *
     * @return string
     * @throws Exception
     */
    public final function expandUrl(string $template, array $data): string
    {
        $tpl = new UriTemplate($template);

        return $tpl->expand($data);
    }
}
