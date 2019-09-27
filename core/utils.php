<?php

use core\{Boot, Router, Input, Output, Config};

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

function boot(string $domain): Boot
{
    return new Boot($domain);
}

function router(string $domain): Router
{
    return new Router($domain);
}

function input($data): Input
{
    return new Input($data);
}

function output($data): Output
{
    return ($data instanceof Output) ? $data : new Output($data);
}

/**
 * get config
 *
 * @param string $key path/file.section.item
 * @param null|mixed $default
 * @return void
 */
function config($key, $default = null)
{
    $pieces = explode('.', $key, 3);
    $blocks = explode(',', env('CONFIG_BLOCK'));
    $cache = env('CONFIG_CACHE');
    return Config::singleton($blocks, $cache)->get(
        $pieces[0],
        $pieces[1] ?? null,
        $pieces[2] ?? null,
        $default
    );
}

function arrayJoin(array $array, ...$arrays)
{
    $arrays = func_get_args();
    foreach ($arrays as $arr) {
        foreach ($arr as $key => $val) {
            if (isset($array[$key]) && is_array($array[$key]) && is_array($val)) {
                $array[$key] = arrayJoin($array[$key], $val);
            } else {
                $array[$key] = $val;
            }
        }
    }
    return $array;
}
