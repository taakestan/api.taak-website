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
if (!function_exists('joinPath')) {
    /**
     * Join the given path with DIRECTORY_SEPARATOR
     *
     * @param array $paths
     * @return string
     */
    function joinPath(...$paths)
    {
        return implode(DIRECTORY_SEPARATOR, $paths);
    }
}
