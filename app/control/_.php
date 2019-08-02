<?php

namespace app\control;

use core\Input;

class _
{
    /**
     * @var Input
     */
    protected $Input;

    public function __construct(Input $Input)
    {
        $this->Input = $Input;
    }

    public function run()
    {
        return 'Welcome to nerv.';
    }
}
