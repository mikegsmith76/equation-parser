<?php

namespace Equation\Tree\Operator;

use Equation\Tree\Operator;

class Square extends Operator
{
    public function value() : float
    {
        return $this->leftOperand->value() ^ $this->rightOperand->value();
    }
}