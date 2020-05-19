<?php

namespace Equation\Tree;

abstract class Operator implements Node
{
    protected $leftOperand;

    protected $rightOperand;

    public function __construct(Node $leftOperand, Node $rightOperand)
    {
        $this->leftOperand = $leftOperand;
        $this->rightOperand = $rightOperand;
    }

    public function getLeftOperand() : Node
    {
        return $this->leftOperand;
    }

    public function getRightOperand() : Node
    {
        return $this->rightOperand;
    }
}