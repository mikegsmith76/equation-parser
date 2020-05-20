<?php

namespace Equation\Lexer;

/**
 * Class Regex
 * 
 * Splits an equation into a list of token strings that can be iterated over
 * 
 * Regex taken from https://github.com/andig/php-shunting-yard/blob/master/src/RR/Shunt/Scanner.php
 */
class Regex implements Lexer
{
    const SYMBOL_ADD = "+";
    const SYMBOL_SUB = "-";
    const SYMBOL_MUL = "*";
    const SYMBOL_DIV = "/";
    const SYMBOL_L_PAREN = "(";
    const SYMBOL_R_PAREN = ")";

    protected $nextTokenIndex = 0;

    protected $pattern = "/^([<>]=|<>|><|[!,><=&\|\+\-\*\/\^%\(\)]|\d*\.\d+|\d+\.\d*|\d+|[a-z_A-Zπ]+[a-z_A-Z0-9]*|[ \t]+)/";

    protected $symbolTokenMap = [
        self::SYMBOL_ADD => Token::T_OPERATOR_ADD,
        self::SYMBOL_SUB => Token::T_OPERATOR_SUB,
        self::SYMBOL_MUL => Token::T_OPERATOR_MUL,
        self::SYMBOL_DIV => Token::T_OPERATOR_DIV,
        self::SYMBOL_L_PAREN => Token::T_L_PAREN,
        self::SYMBOL_R_PAREN => Token::T_R_PAREN,
    ];

    protected $tokens = [];

    public function analyse(string $tokenString) : void
    {
        $this->evaluateTokenString($tokenString);
    }

    public function hasMoreTokens() : bool
    {
        return $this->nextTokenIndex < count($this->tokens);
    }

    public function getNextToken() : Token
    {
        if (!$this->hasMoreTokens()) {
            throw new Exception("No More Tokens Available");
        }
        return $this->tokens[$this->nextTokenIndex++];
    }

    protected function evaluateTokenString(string $tokenString) : void
    {
        // reset internal tokens list
        $this->nextTokenIndex = 0;
        $this->tokens = [];

        while(strlen($tokenString)) {
            $segments = [];
            preg_match($this->pattern, $tokenString, $segments);

            $tokenString = substr($tokenString, strlen($segments[1]));
            $tokenValue = trim($segments[1]);

            if (empty($tokenValue)) {
                continue;
            }

            $token = null;

            if (is_numeric($tokenValue)) {
                $token = new Token(Token::T_NUMBER, $tokenValue);
            }

            if (!empty($this->symbolTokenMap[$tokenValue])) {
                $token = new Token($this->symbolTokenMap[$tokenValue]);
            }

            if (null === $token) {
                throw new \Exception("Token could not be matched");
            }

            $this->tokens[] = $token;
        }
    }
}