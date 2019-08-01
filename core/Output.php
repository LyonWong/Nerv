<?php

namespace core;


class Output
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function send()
    {
        print_r($this->data);
    }

}