<?php

namespace KirbyReporter\Client;

use KirbyReporter\Mixins\ArrayTransformator;

/**
 * RequestDataMapper
 *
 * @package KirbyReporter\Client
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class RequestDataMapper
{
    use ArrayTransformator;

    public $mappedData;

    public function __construct(array $responseBody, array $fieldMap)
    {
        $this->mappedData = $this->transform($responseBody, $fieldMap);
    }

    public function getMappedData()
    {
        return $this->mappedData;
    }
}
