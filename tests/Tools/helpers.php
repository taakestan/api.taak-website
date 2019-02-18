<?php

if (!function_exists('create')) {
    /**
     * wrapper for factory()->create()
     *
     * @param string $class
     * @param array $attribute
     * @param null $times
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection
     */
    function create(string $class, array $attribute = [], $times = null)
    {
        return factory($class, $times)->create($attribute);
    }
}

if (!function_exists('make')) {
    /**
     * wrapper for factory()->make()
     *
     * @param string $class
     * @param array $attribute
     * @param null $times
     * @return \Illuminate\Database\Eloquent\Model
     */
    function make(string $class, array $attribute = [], $times = null)
    {
        return factory($class, $times)->make($attribute);
    }
}

if (!function_exists('raw')) {
    /**
     * wrapper for factory()->raw()
     *
     * @param string $class
     * @param array $attribute
     * @param null $times
     * @return array
     */
    function raw(string $class, array $attribute = [], $times = null)
    {
        return factory($class, $times)->raw($attribute);
    }
}
