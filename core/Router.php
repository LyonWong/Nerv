<?php

namespace core;


class Router
{
    private $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function resolve($URI)
    {
        $path = preg_replace('#/+#', '\\', parse_url($URI)['path']);
        $args = explode('-', $path);

        if ($args[0] == '\\') {
            $args[0] .= '_';
        }

        return [
            'ctrl' => "$this->domain\\control$args[0]",
            'args' => array_slice($args, 1),
        ];
    }

}