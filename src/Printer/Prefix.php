<?php

namespace Equation\Printer;

use Equation\Tree\Node;

class Prefix extends Printer
{
    public function print(Node $equation) : string
    {
        if (!$this->isOperatorNode($equation)) {
            return (string) $equation->value();
        }

        $leftOperand = $this->print($equation->getLeftOperand());
        $rightOperand = $this->print($equation->getRightOperand());
        $operator = $this->getOperator($equation);

        return "{$operator} {$leftOperand} {$rightOperand}";
    }
}