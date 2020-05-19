<?php

namespace Equation\Lexer;

class Token
{
    const T_UNKNOWN = 0;
    const T_NUMBER = 1;
    const T_OPERATOR_ADD = 2;
    const T_OPERATOR_SUB = 3;
    const T_OPERATOR_MUL = 4;
    const T_OPERATOR_DIV = 5;
    const T_OPERATOR_POW = 6;
    const T_L_PAREN = 7;
    const T_R_PAREN = 8;

    protected $type;
    protected $value;

    public function __construct(int $type, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType() : int
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }
}