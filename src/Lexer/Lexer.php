<?php

namespace Equation\Lexer;

interface Lexer
{
    public function analyse(string $tokenString) : void;

    public function hasMoreTokens() : bool;

    public function getNextToken() : Token;
}