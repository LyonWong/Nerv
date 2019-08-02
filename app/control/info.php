<?php

namespace app\control;

class info extends _
{
    public function run()
    {
        return output($this->Input->_SERVER)->json()->header("foo:bar");
    }
}