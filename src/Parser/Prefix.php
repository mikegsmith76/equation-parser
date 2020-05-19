<?php

namespace Equation\Parser;

use Equation\Lexer;
use Equation\Lexer\Token;
use Equation\Tree\Node;
use Equation\Tree\Number;
use Equation\Tree\Operator\Add;
use Equation\Tree\Operator\Subtract;
use Equation\Tree\Operator\Multiply;
use Equation\Tree\Operator\Divide;

class Prefix
{
    protected $lexer;

    protected $tokenMap = [
        Token::T_OPERATOR_ADD => Add::class,
        Token::T_OPERATOR_SUB => Subtract::class,
        Token::T_OPERATOR_MUL => Multiply::class,
        Token::T_OPERATOR_DIV => Divide::class,
    ];

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse(string $tokenString) : Node
    {
        $this->lexer->setTokenString($tokenString);

        if (!$this->lexer->hasMoreTokens()) {
            throw new Exception("Invalid Expression");
        }

        try {
            $expression = $this->getNode($this->lexer->getNextToken());

        } catch (Exception $exception) {
            throw new Exception("Invalid Expression");
        }
        
        if ($this->lexer->hasMoreTokens()) {
            throw new Exception("Invalid Expression");
        }

        return $expression;
    }

    public function getNode(Token $token) : Node
    {
        if (Token::T_NUMBER === $token->getType()) {
            return new Number((float) $token->getValue());
        }

        if (empty($this->tokenMap[$token->getType()])) {
            throw new Exception("Invalid Expression");
        }

        $leftOperand = $this->getNode($this->lexer->getNextToken());
        $rightOperand = $this->getNode($this->lexer->getNextToken());

        return new $this->tokenMap[$token->getType()](
            $leftOperand,
            $rightOperand
        );
    }
}