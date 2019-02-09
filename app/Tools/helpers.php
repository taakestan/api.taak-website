<?php

if (!function_exists('wrap_with_data')) {
    /**
     * change byte to human
     *
     * @param array $data
     * @param string $wrapWord
     * @return array
     */
    function wrap_with_data(array $data , $wrapWord = 'data')
    {

        if (is_null($data)) {
            return compact('wrapWord');
        }

        return is_array($data) ? [$wrapWord => $data] : [$wrapWord => [$data]];
    }
}