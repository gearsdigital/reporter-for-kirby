<?php

namespace KirbyReporter\Mixins;

/**
 * ArrayTransformator is a simple array manipulation helper.
 *
 * @package KirbyReporter\Client
 * @author  Steffen Giers <steffen.giers@gmail.com>
 */
trait ArrayTransformator
{

    /**
     * Transform array keys based on key map.
     *
     * External APIs may expect data which differs from provided data. This method will replace
     * all keys in given $input array by using $hashMap as reference.
     *
     * @param  array  $input  Datasource on which the map is applied
     * @param  array  $hashMap
     *
     * @return  array
     * @example
     *
     * $map = ['title' => 'name'];
     * $input = ['title' => 'Hello World!']
     *
     * -> ['name' => 'Hello World!']
     */
    private function transform(array $input, array $hashMap): array
    {
        if (empty($hashMap)) {
            return $input;
        }
        foreach ($hashMap as $key => $value) {
            $input = $this->replaceKey($input, $key, $value);
        }

        return $input;
    }

    /**
     * Replace array key
     *
     * @param  array  $input
     * @param  string  $key
     * @param  string  $value
     *
     * @return array
     */
    private function replaceKey(array $input, string $key, string $value): array
    {
        $needle = array_search($input[$key], $input);
        if ($needle != $value) {
            $input[$value] = $input[$needle];
            unset($input[$key]);
        }

        return $input;
    }
}
