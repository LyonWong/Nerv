<?php

namespace core\plugin;

trait instance
{
    protected static $singletons = [];

    public static function singleton(...$args)
    {
        return new static(...$args);
    }
}