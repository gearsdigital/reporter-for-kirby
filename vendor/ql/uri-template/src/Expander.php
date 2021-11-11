<?php
/**
 * @copyright ©2013 Quicken Loans Inc. All rights reserved.
 */

namespace QL\UriTemplate;

/**
 * Implements RFC 6570 Level 4
 *
 * It is done without regular expression. This way lies madness! The code is
 * not very neat, but it is tested extremely well.
 *
 * This only expands URI templates in the UTF-8 character set. Templates in any
 * other character set will immediately error and return.
 *
 * @api
 */
class Expander
{
    const ENC = 'UTF-8';

    /**
     * @var array
     */
    private static $behavior = [
        ''  => [ 'first' => '',  'sep' => ',', 'named' => false, 'ifemp' => '',  'allow' => 'U'   ],
        '+' => [ 'first' => '',  'sep' => ',', 'named' => false, 'ifemp' => '',  'allow' => 'U+R' ],
        '.' => [ 'first' => '.', 'sep' => '.', 'named' => false, 'ifemp' => '',  'allow' => 'U'   ],
        '/' => [ 'first' => '/', 'sep' => '/', 'named' => false, 'ifemp' => '',  'allow' => 'U'   ],
        ';' => [ 'first' => ';', 'sep' => ';', 'named' => true,  'ifemp' => '',  'allow' => 'U'   ],
        '?' => [ 'first' => '?', 'sep' => '&', 'named' => true,  'ifemp' => '=', 'allow' => 'U'   ],
        '&' => [ 'first' => '&', 'sep' => '&', 'named' => true,  'ifemp' => '=', 'allow' => 'U'   ],
        '#' => [ 'first' => '#', 'sep' => ',', 'named' => false, 'ifemp' => '',  'allow' => 'U+R' ],
    ];

    /**
     * Error from last invokation
     *
     * @var string|null
     */
    private $error;

    /**
     * Returns the error (if any) from the last expansion
     *
     * @return string|null
     */
    public function lastError()
    {
        return $this->error;
    }

    /**
     * @param string $tpl
     * @param array $variables
     * @param array $options
     *
     * @return string
     */
    public function __invoke($tpl, array $variables, array $options = [])
    {
        $this->error = null;

        if (!mb_check_encoding($tpl, self::ENC)) {
            $this->error = 'Input template not valid UTF-8';
            return $tpl;
        }

        $result = '';
        $error = null;

        $error = $this->checkVariableStructure($variables);
        if ($error) {
            $this->error = $error;
            return $tpl;
        }

        $tplLen = mb_strlen($tpl, self::ENC);
        for ($i = 0; $i < $tplLen; $i++) {
            $chr = mb_substr($tpl, $i, 1, self::ENC);

            if ($error) {
                $result .= $chr;
                continue;
            }

            if (strlen($chr) === 1 && $chr !== '{') {
                $isValid = $this->allowedSingleByteLiteral($chr);
                if (true === $isValid) {
                    $result .= $chr;
                    continue;
                } else if (false === $isValid) {
                    $result .= $chr;
                    $error = "Invalid character at position $i: $tpl";
                } else {
                    $this->validPctEncode($tpl, $i, $result, $error);
                }
            } else if ($chr === '{') {
                $origPos = $i;
                $expr = $this->scanExpression($tpl, $i, $error);
                if ($error) {
                    $result .= '{' . $expr;
                    continue;
                }
                if ($expr === '') {
                    $result .= "{}";
                    $error = "Empty expression at position $origPos: $tpl";
                    continue;
                }
                $op = $this->extractOperator($expr, $origPos, $tpl);
                if (false === $op) {
                    $result .= '{' . $expr . '}';
                    $origPos++;
                    $error = "Invalid operator at position $origPos: $tpl";
                    continue;
                }
                if ($op) {
                    $expr = substr($expr, 1);
                }
                $expandResult = $this->expand($expr, $op, $variables, $options);
                if (false === $expandResult) {
                    $error = "Invalid expression at position $origPos: $tpl";
                    $result .= '{' . $expr . '}';
                    continue;
                }
                $result .= $expandResult;
            } else {
                $result .= $chr;
            }
        }

        $this->error = $error;
        return $result;
    }

    /**
     * Expands a single expression
     *
     * @param string $expr The expression string minus the operator
     * @param string|null $op The operator (or null if no operator)
     * @param array $variables The varibles to expand with
     * @param array $options
     *
     * @return string|boolean Returns the expanded string or false if there was
     *    an error with the expansion format.
     */
    private function expand($expr, $op, array $variables, array $options = [])
    {
        $result = '';
        $varspecs = explode(',', $expr);

        foreach ($varspecs as &$varspec) {
            $varname = '';
            $varspeclen = strlen($varspec);
            $mod = '';
            for ($i = 0; $i < $varspeclen; $i++) {
                $chr = $varspec[$i];
                $validChr = $this->isValidVarChr($chr);

                if (true === $validChr) {
                    $varname .= $chr;
                    continue;
                }

                if (null === $validChr) {
                    $this->validPctEncode($varspec, $i, $varname, $error);
                    if ($error) {
                        return false;
                    } else {
                        continue;
                    }
                }

                if ($chr === '*') {
                    if ($i + 1 === $varspeclen) {
                        $mod = '*';
                    } else {
                        return false;
                    }
                } else if ($chr === ':') {
                    $mod = $this->scanLengthModifier($varspec, $i, $error);
                    if (!$mod) {
                        return false;
                    }
                } else {
                    return false;
                }
            }

            $varspec = [$varname, $mod];
        }

        $isFirst = true;
        $unusedVars = [];
        foreach ($varspecs as $spec) {
            if (!isset($variables[$spec[0]])) {
                $unusedVars[] = $spec[0];
                continue;
            }
            if (is_array($variables[$spec[0]]) && $variables[$spec[0]] === []) {
                continue;
            }
            if ($isFirst) {
                $result .= self::$behavior[$op]['first'];
                $isFirst = false;
            } else {
                $result .= self::$behavior[$op]['sep'];
            }
            $value = $variables[$spec[0]];
            if (is_scalar($value) || is_object($value)) {
                $value = (string) $value;
                if (self::$behavior[$op]['named']) {
                    $result .= $spec[0];
                    if ($value === '') {
                        $result .= self::$behavior[$op]['ifemp'];
                        continue;
                    } else {
                        $result .= '=';
                    }
                }

                if ($spec[1] && $spec[1] !== '*') {
                    if (mb_strlen($value, self::ENC) > $spec[1]) {
                        $value = mb_substr($value, 0, $spec[1], self::ENC);
                    }
                }

                if (self::$behavior[$op]['allow'] === 'U') {
                    $result .= rawurlencode($value);
                } else {
                    $result .= $this->encodeNonReservedCharacters($value);
                }
            } else if ($spec[1] !== '*') {
                if (self::$behavior[$op]['named']) {
                    $result .= $spec[0];
                    $result .= '=';
                }
                if (!$this->isNumericArray($value)) {
                    $newval = [];
                    foreach ($value as $k => $v) {
                        $newval[] = $k;
                        $newval[] = $v;
                    }
                    if (self::$behavior[$op]['allow'] === 'U') {
                        $value = array_map('rawurlencode', $newval);
                    } else {
                        $value = array_map([$this, 'encodeNonReservedCharacters'], $newval);
                    }
                }
                $result .= implode(',', $value);
            } else {
                $result .= $this->expandExplodeMod($op, $spec[0], $value);
            }
        }

        if (isset($options["preserveTpl"]) && $options["preserveTpl"] && count($unusedVars) > 0) {
            $result .= "{";
            if ($isFirst) {
                $result .= self::$behavior[$op]['first'];
                $isFirst = false;
            } else {
                $result .= self::$behavior[$op]['sep'];
            }
            $result .= implode(',', $unusedVars);
            $result .= "}";
        }

        return $result;
    }

    /**
     * Does the work of the explode modifier
     *
     * @param string|null $op
     * @param string $varname
     * @param array $varvalue
     * @return string
     */
    private function expandExplodeMod($op, $varname, array $varvalue)
    {
        $result = '';
        $first = true;
        $isList = $this->isNumericArray($varvalue);
        if (self::$behavior[$op]['named']) {
            foreach ($varvalue as $name => $value) {
                if ($first) {
                    $first = false;
                } else {
                    $result .= self::$behavior[$op]['sep'];
                }
                if ($isList) {
                    $result .= $varname;
                } else {
                    $result .= $name;
                }
                if (!$value) {
                    $result .= self::$behavior[$op]['ifemp'];
                } else {
                    $result .= '=' . rawurlencode($value);
                }
            }
        } else {
            if ($isList) {
                if (self::$behavior[$op]['allow'] === 'U') {
                    $varvalue = array_map('rawurlencode', $varvalue);
                } else {
                    $varvalue = array_map([$this, 'encodeNonReservedCharacters'], $varvalue);
                }
                $result .= implode(self::$behavior[$op]['sep'], $varvalue);
            } else {
                $newvals = [];
                foreach ($varvalue as $name => $value) {
                    if (self::$behavior[$op]['allow'] === 'U') {
                        $newvals[] .= rawurlencode($name) . '=' . rawurlencode($value);
                    } else {
                        $newvals[] .= $this->encodeNonReservedCharacters($name) . '=' . $this->encodeNonReservedCharacters($value);
                    }
                }
                $result .= implode(self::$behavior[$op]['sep'], $newvals);
            }
        }

        return $result;
    }

    /**
     * This method decides if the given array is a 'list' or an 'associative array'
     *
     * The RFC defines variable data to be either a string, a list or an
     * associative array of name, value pairs. Since PHP's array construct can
     * be either a list OR an associative array, this method does the
     * differentiation.
     *
     * @param array $arr
     * @return boolean
     */
    private function isNumericArray(array $arr)
    {
        $numeric = true;
        $i = 0;
        foreach ($arr as $k => $v) {
            if ($i !== $k) {
                $numeric = false;
                return $numeric;
            }
            $i++;
        }
        return $numeric;
    }

    /**
     * Does a rawurlencode() on the string skipping the RFC's 'reserved' characters.
     *
     * @param string $ipt
     * @return string
     */
    private function encodeNonReservedCharacters($ipt)
    {
        $result = '';
        $len = strlen($ipt);
        for ($i = 0; $i < $len; $i++) {
            $chr = $ipt[$i];
            if (
                $chr === ':' ||
                $chr === '/' ||
                $chr === '?' ||
                $chr === '#' ||
                $chr === '[' ||
                $chr === ']' ||
                $chr === '@' ||
                $chr === '!' ||
                $chr === '$' ||
                $chr === '&' ||
                $chr === "'" ||
                $chr === '(' ||
                $chr === ')' ||
                $chr === '*' ||
                $chr === '+' ||
                $chr === ',' ||
                $chr === ';' ||
                $chr === '='
            ) {
                $result .= $chr;
            } else {
                $result .= rawurlencode($chr);
            }
        }
        return $result;
    }

    /** 
     * Given a full expression (all text between { and }) return the operator.
     *
     * @param string $expr
     * @param int $origPos
     * @param string $tpl
     * @return bool|null
     */
    private function extractOperator($expr, $origPos, $tpl)
    {
        $op = $expr[0];
        $validOp = $this->isValidOperator($op);
        if ($validOp) {
            return $op;
        }

        $validVarChr = $this->isValidVarChr($op);
        if (false === $validVarChr) {
            return false;
        } else if (null === $validVarChr) {
            $pctEnc = substr($expr, 1, 2);
            if (!ctype_xdigit($pctEnc) || strlen($pctEnc) !== 2) {
                return false;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @param string $chr
     * @return bool
     */
    private function isValidOperator($chr)
    {
        if (
            $chr === '+' ||
            $chr === '#' ||
            $chr === '.' ||
            $chr === '/' ||
            $chr === ';' ||
            $chr === '?' ||
            $chr === '&'
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param $tpl
     * @param $i
     * @param $error
     * @return bool|int
     */
    private function scanLengthModifier($tpl, &$i, &$error)
    {
        $mod = substr($tpl, $i + 1);
        if (strlen($mod) < 1 || strlen($mod) > 4) {
            $error = 'Invalid modifier';
            return false;
        }

        if (ctype_digit($mod) && $mod[0] !== '0') {
            $i += strlen($mod) + 1;
            return (int) $mod;
        }

        $error = 'Invalid modifier';
        return false;
    }

    /**
     * @param string $tpl
     * @param integer $i
     * @param string $result
     * @param string|null $error
     * @return null
     */
    private function validPctEncode($tpl, &$i, &$result, &$error)
    {
        $chr1 = isset($tpl[$i + 1]) ? $tpl[$i + 1] : null;
        $chr2 = isset($tpl[$i + 2]) ? $tpl[$i + 2] : null;

        $result .= $tpl[$i] . $chr1 . $chr2;
        $k = $i;
        $i += 2;

        if (null === $chr1 || null === $chr2) {
            $error = "Invalid pct-encode at position $k: $tpl";
            return;
        }

        $hexstr = $chr1 . $chr2;

        if (!ctype_xdigit($hexstr)) {
            $error = "Invalid pct-encode at position $k: $tpl";
        }
    }

    /**
     * @param string
     * @return boolean
     */
    private function allowedSingleByteLiteral($chr)
    {
        $num = ord($chr);
        if ($num < 0x21) {
            return false;
        }

        if (
            $num === 0x22 ||
            $num === 0x27 ||
            $num === 0x3C ||
            $num === 0x3E ||
            $num === 0x5C ||
            $num === 0x5E ||
            $num === 0x60 ||
            $num === 0x7C
        ) {
            return false;
        }

        if ($num === 0x25) {
            return null;
        }

        return true;
    }

    /**
     * @param $tpl
     * @param $i
     * @param $error
     * @return string
     */
    private function scanExpression($tpl, &$i, &$error)
    {
        $expression = '';
        $initial = $i;
        while (true) {
            $i++;
            $chr = mb_substr($tpl, $i, 1, self::ENC);
            if ($chr === '') {
                $error = "Unclosed expression at offset $initial: $tpl";
                break;
            }
            if ($chr === '}') {
                break;
            } else {
                $expression .= $chr;
            }
        }
        return $expression;
    }

    /**
     * @param $chr
     * @return bool|null
     */
    private function isValidVarChr($chr)
    {
        $num = ord($chr);

        if (
            ($num >= 0x30 && $num <= 0x39) ||
            ($num >= 0x41 && $num <= 0x5A) ||
            ($num >= 0x61 && $num <= 0x7A) ||
            $num === 0x5F
        ) {
            return true;
        }

        if ($num === 0x25) {
            return null;
        }

        return false;
    }

    /**
     * @param array $variables
     * @param bool $arrayAllowed
     * @return null|string
     */
    private function checkVariableStructure(array $variables, $arrayAllowed = true)
    {
        foreach ($variables as $val) {
            if (is_resource($val)) {
                return 'Resources are not allowed as variable values.';
            }

            if (is_object($val) && !method_exists($val , '__toString')) {
                return 'Objects without a __toString() method are not allowed as variable values.';
            }

            if (is_array($val) && $arrayAllowed) {
                $error = $this->checkVariableStructure($val, false);
                if ($error) {
                    return $error;
                }
            }

            if (is_array($val) && !$arrayAllowed) {
                return 'Variable values may not be arrays that include other arrays as values.';
            }
        }

        return null;
    }
}
