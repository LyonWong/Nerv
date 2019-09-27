<?php

namespace core\plugin;

trait invoke 
{
    public function run($action='', ...$args)
    {
        $method = $this->Input->METHOD;
        $actionMethod = '_'.$method.'_'.$action;
        $defaultMethod = '_DO_'.$action;
        if (method_exists($this, $actionMethod)) {
            return $this->$actionMethod(...$args);
        } elseif (method_exists($this, $defaultMethod)) {
            return $this->$defaultMethod(...$args);
        } else {
            return false;
        }
    }
}