<?php

namespace Controller;

class Controller
{
    private $parameters = array();

    public function __construct()
    {
        $args = func_get_args();
        $this->parameters = $args[0];
    }

    protected function getParameters()
    {
        return $this->parameters;
    }
}