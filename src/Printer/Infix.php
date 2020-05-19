<?php

namespace Equation\Printer;

use Equation\Tree\Node;

class Infix extends Printer
{
    public function print(Node $node) : string
    {
        if (!$this->isOperatorNode($node)) {
            return (string) $node->value();
        }

        $leftOperand = $this->print($node->getLeftOperand());
        $rightOperand = $this->print($node->getRightOperand());
        $operator = $this->getOperator($node);

        return "({$leftOperand}{$operator}{$rightOperand})";
    }
}