<?php

namespace Equation\Printer;

use Equation\Tree\Node;
use Equation\Tree\Operator\Add;
use Equation\Tree\Operator\Subtract;
use Equation\Tree\Operator\Multiply;
use Equation\Tree\Operator\Divide;

abstract class Printer
{
    protected $tokenMap = [
        Add::class => "+",
        Subtract::class => "-",
        Multiply::class => "*",
        Divide::class => "/",
    ];

    protected function isOperatorNode(Node $node) : bool
    {
        return !empty($this->tokenMap[get_class($node)]);
    }

    public function getOperator(Node $node) : string
    {
        return $this->tokenMap[get_class($node)];
    }

    abstract public function print(Node $expression) : string;
}