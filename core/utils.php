<?php

use core\{Boot, Router, Input, Output};

function _autoload(string $class)
{
    $file = realpath(PATH_ROOT . '/' . str_replace('\\', '/', $class) . '.php');
    if ($file) {
        require_once $file;
    }
}

function boot(string $domain):Boot
{
    return new Boot($domain);
}

function router(string $domain):Router
{
    return new Router($domain);
}

function input($data):Input
{
    return new Input($data);
}

function output($data):Output
{
    return new Output($data);
}
