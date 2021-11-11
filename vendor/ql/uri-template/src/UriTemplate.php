<?php
/**
 * @copyright ©2013 Quicken Loans Inc. All rights reserved.
 */

namespace QL\UriTemplate;

/**
 * An instance of this class is a valid URI Template
 *
 * The idea of this class is to provide a 'strict' wrapper around the Expander
 * class that does the real expansion work. It mainly will check if a given
 * string is a valid URI Template on construction so if you get an instance of
 * this class you can guarantee the format of the template is valid.
 *
 * @api
 */
class UriTemplate
{
    /**
     * @var Expander
     */
    private $expander;

    /**
     * @var string
     */
    private $tpl;

    /**
     * @param string $tpl
     * @param Expander|null $expander
     * @throws Exception
     */
    public function __construct($tpl, Expander $expander = null)
    {
        if (is_null($expander)) {
            $expander = new Expander;
        }

        $expander($tpl, []);
        $error = $expander->lastError();
        if ($error) {
            throw new Exception($error);
        }
        $this->expander = $expander;
        $this->tpl = $tpl;
    }

    /**
     * @param array $variables
     * @throws Exception
     * @return string
     */
    public function expand(array $variables = [])
    {
        $result = call_user_func($this->expander, $this->tpl, $variables);

        if ($this->expander->lastError()) {
            throw new Exception($this->expander->lastError());
        }

        return $result;
    }
}
