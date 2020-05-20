<?php

namespace Equation\Mocks;

use Equation\Lexer\Lexer as LexerInterface;
use Equation\Lexer\Token;

class Lexer implements LexerInterface
{
    protected $tokens = [];

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function analyse(string $tokenString) : void
    {
    }

    public function hasMoreTokens() : bool
    {
        return count($this->tokens);
    }

    public function getNextToken() : Token
    {
        return array_shift($this->tokens);
    }
}