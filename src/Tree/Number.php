<?php

namespace Equation\Tree;

class Number implements Node
{
    protected $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function value() : float
    {
        return $this->value;
    }
}