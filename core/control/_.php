<?php

namespace core\control;

use core\Input;
use core\plugin\invoke;

class _
{
    use invoke;

    protected $Input;

    public function __construct(Input $Input)
    {
        $this->Input = $Input;
    }
}