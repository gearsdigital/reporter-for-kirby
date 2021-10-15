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
     * This method will replace all array keys in given $input by using $hashMap as reference.
     * If the $input value is an array it will check for an "dot path" and create a nested array structure.
     *
     * @param  array  $input  Datasource on which the map is applied
     * @param  array  $hashMap  Key-Value store of key to be replaced
     *
     * @return  array
     * @example
     *
     * $map = ['title' => 'name'];
     * $input = ['title' => 'Hello World!']
     *
     * -> ['name' => 'Hello World!']
     */
    public function transform(array $input, array $hashMap): array
    {
        if (empty($hashMap) || empty($input)) {
            return $input;
        }
        foreach ($hashMap as $key => $value) {
            if ($key != $value) {
                if (is_array($value)) {
                    $this->replaceByPath($input, $value[0], $input[$key]);
                } else {
                    $this->replace($input, $key, $value);
                }
                unset($input[$key]);
            }
        }

        return $input;
    }

    /**
     * Replace key in array
     *
     * @param  array  $haystack
     * @param  string  $needle
     * @param  string  $value
     *
     * @return array
     */
    private function replace(array &$haystack, string $needle, $value): array
    {
        if ($needle != $value) {
            $haystack[$value] = $haystack[$needle];
        }

        return $haystack;
    }

    /**
     * Replace by path
     *
     * @param $arr
     * @param $path
     * @param $value
     * @param  string  $separator
     */
    private function replaceByPath(&$arr, $path, $value, $separator = '.')
    {
        $keys = explode($separator, $path);
        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }
        $arr = $value;
    }
}
