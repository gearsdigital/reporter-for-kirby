<?php

namespace KirbyReporter\Client;

use Exception;
use KirbyReporter\Mixins\ArrayTransformator;
use ReflectionObject;
use ReflectionProperty;

/**
 * ResponseMapper
 *
 * Public properties define the response object. If you're going to extend the response
 * object you have to make sure to adapt all implented maps.
 *
 * @package KirbyReporter\Client
 * @author Steffen Giers <steffen.giers@gmail.com>
 */
class ResponseMapper
{

    use ArrayTransformator;

    /**
     * HTTP Status code
     *
     * @var int
     */
    public $status;

    /**
     * @var string | null
     */
    public $issueId;

    /**
     * @var string | null
     */
    public $issueUrl;

    /**
     * @var array
     */
    private $map;

    /**
     * @var array
     */
    private $response;

    /**
     * ResponseMapper constructor.
     *
     * @param  Response  $response
     *
     * @param  array  $map
     *
     * @throws Exception
     */
    public function __construct(Response $response, array $map)
    {
        $this->map = $map;
        $this->response = $response->body;
        if (!empty($this->map)) {
            $this->populateProperties();
        }
        $this->validateProperties();
        // This needs to happen after all transformations are done because
        // the status is always populated
        if (is_numeric($response->status)) {
            $this->status = $response->status;
        }
    }

    /**
     * Assignes response entry values to matching public properties.
     *
     * @return void;
     */
    private function populateProperties(): void
    {
        $mappedResponse = $this->applyMapToProperties();
        foreach (get_object_vars($this) as $propertyName => $propertyValue) {
            $this->$propertyName = $mappedResponse[$propertyName];
        }
    }

    /**
     * Applies all map entries to the reduced response object.
     *
     * @return array
     */
    private function applyMapToProperties(): array
    {
        return $this->transform($this->reduceResponseWithMap(), $this->map);
    }

    /**
     * Removes all entries from response array which are not present in map.
     *
     * @return array
     */
    private function reduceResponseWithMap(): array
    {
        return array_intersect_key($this->response, $this->map);
    }

    /**
     * Validate that all response properties are mapped.
     *
     * The Frontend has specific requirements ss we need to make sure
     * all entries are mapped correctly :)
     *
     * @throws Exception
     */
    private function validateProperties(): void
    {
        $properties = $this->getPublicProperties();
        if (in_array(null, $properties)) {
            throw new Exception('You need to implement all required fields.');
        }
    }

    /**
     * Returns all public properties of this very class.
     *
     * @return array
     */
    private function getPublicProperties(): array
    {
        $reflectionClass = new ReflectionObject($this);

        return $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
    }

    public function setIssueId($id)
    {
        $this->issueId = $id;
    }

    public function setissueUrl($url)
    {
        $this->issueUrl = $url;
    }

}
