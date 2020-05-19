<?php

namespace Equation;

use Equation\Lexer\Token;

class Lexer
{
    const SYMBOL_ADD = "+";
    const SYMBOL_SUB = "-";
    const SYMBOL_MUL = "*";
    const SYMBOL_DIV = "/";
    const SYMBOL_L_PAREN = "(";
    const SYMBOL_R_PAREN = ")";

    protected $nextTokenIndex = 0;

    protected $symbolTokenMap = [
        self::SYMBOL_ADD => Token::T_OPERATOR_ADD,
        self::SYMBOL_SUB => Token::T_OPERATOR_SUB,
        self::SYMBOL_MUL => Token::T_OPERATOR_MUL,
        self::SYMBOL_DIV => Token::T_OPERATOR_DIV,
        self::SYMBOL_L_PAREN => Token::T_L_PAREN,
        self::SYMBOL_R_PAREN => Token::T_R_PAREN,
    ];

    protected $tokens = [];

    public function setTokenString(string $tokenString) : void
    {
        $this->nextTokenIndex = 0;
        $this->evaluateTokenString($tokenString);
    }

    public function hasMoreTokens() : bool
    {
        return $this->nextTokenIndex < count($this->tokens);
    }

    public function getNextToken()
    {
        if (!$this->hasMoreTokens()) {
            throw new Exception("No More Tokens Available");
        }
        return $this->tokens[$this->nextTokenIndex++];
    }

    protected function evaluateTokenString(string $tokenString) : void
    {
        $segments = array_map("trim", explode(" ", $tokenString));

        foreach ($segments as $segment) {
            if (is_numeric($segment)) {
                $this->tokens[] = new Token(Token::T_NUMBER, $segment);
                continue;
            }

            if (!empty($this->symbolTokenMap[$segment])) {
                $this->tokens[] = new Token($this->symbolTokenMap[$segment]);
                continue;
            }

            // functions etc
        }
    }
}