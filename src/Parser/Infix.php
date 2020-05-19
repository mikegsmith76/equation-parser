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

/**
 * Class Infix
 * 
 * Infix expression parser based off Shunting Yard algorithm but modified to generate an AST of sorts
 * 
 * https://en.wikipedia.org/wiki/Shunting-yard_algorithm
 */
class Infix
{
    const ASSOC_LEFT = 1;
    const ASSOC_RIGHT = 2;

    protected $lexer;

    protected $operatorAssoc = [
        Token::T_OPERATOR_POW => self::ASSOC_RIGHT,
        Token::T_OPERATOR_MUL => self::ASSOC_LEFT,
        Token::T_OPERATOR_DIV => self::ASSOC_LEFT,
        Token::T_OPERATOR_ADD => self::ASSOC_LEFT,
        Token::T_OPERATOR_SUB => self::ASSOC_LEFT,
    ];

    protected $operatorPrecedences = [
        Token::T_OPERATOR_POW => 3,
        Token::T_OPERATOR_MUL => 2,
        Token::T_OPERATOR_DIV => 2,
        Token::T_OPERATOR_ADD => 1,
        Token::T_OPERATOR_SUB => 1,
    ];

    protected $operatorStack;

    protected $outputStack;

    protected $tokenMap = [
        Token::T_OPERATOR_ADD => Add::class,
        Token::T_OPERATOR_SUB => Subtract::class,
        Token::T_OPERATOR_MUL => Multiply::class,
        Token::T_OPERATOR_DIV => Divide::class,
    ];

    public function __construct(
        Lexer $lexer,
        \SplStack $operatorStack,
        \SplStack $outputStack
    )
    {
        $this->lexer = $lexer;
        $this->operatorStack = $operatorStack;
        $this->outputStack = $outputStack;
    }

    public function parse(string $tokenString) : Node
    {
        $this->lexer->setTokenString($tokenString);

        while ($this->lexer->hasMoreTokens()) {
            $currentToken = $this->lexer->getNextToken();

            switch ($currentToken->getType()) {
                case Token::T_NUMBER:
                    $this->outputStack->push(
                        new Number($currentToken->getValue())
                    );
                break;

                case Token::T_L_PAREN:
                    $this->operatorStack->push($currentToken);
                break;

                case Token::T_R_PAREN:
                    $matched = false;

                    while (!$this->operatorStack->isEmpty()) {
                        $stackToken = $this->operatorStack->pop();
    
                        if (Token::T_L_PAREN === $stackToken->getType()) {
                            $matched = true;
                            break;
                        }
    
                        $this->addOperatorNodeToStack($stackToken->getType(), $this->outputStack);
                    }
    
                    if (!$matched) {
                        throw new \Exception("Mismatched parenthesis");
                    }
                break;

                case Token::T_OPERATOR_ADD:
                case Token::T_OPERATOR_SUB:
                case Token::T_OPERATOR_MUL:
                case Token::T_OPERATOR_DIV:
                case Token::T_OPERATOR_POW:
                    while (!$this->operatorStack->isEmpty()) {
                        $stackToken = $this->operatorStack->bottom();
        
                        if (Token::T_L_PAREN === $stackToken->getType()) {
                            break;
                        }
        
                        $currentTokenPrecedence = $this->operatorPrecedences[$currentToken->getType()];
                        $stackTokenPrecedence = $this->operatorPrecedences[$stackToken->getType()];
        
                        if ($stackTokenPrecedence < $currentTokenPrecedence) {
                            break;
                        }
        
                        if (!($stackTokenPrecedence === $currentTokenPrecedence && $this->operatorAssoc[$currentToken->getType()] === self::ASSOC_LEFT)) {
                            break;
                        }
        
                        $stackToken = $this->operatorStack->pop();
                        $this->addOperatorNodeToStack($stackToken->getType(), $this->outputStack);
                    }
        
                    $this->operatorStack->push($currentToken);
                break;
            }
        }

        while (!$this->operatorStack->isEmpty()) {
            $stackToken = $this->operatorStack->pop();
            $this->addOperatorNodeToStack($stackToken->getType(), $this->outputStack);
        }

        if (count($this->outputStack) > 1) {
            throw new Exception("Invalid Expression");
        }

        return $this->outputStack->pop();
    }

    protected function addOperatorNodeToStack(int $type, \SplStack $stack)
    {
        if (empty($this->tokenMap[$type])) {
            throw new \Exception("Invalid Expression");
        }

        $rightNode = $stack->pop();
        $leftNode = $stack->pop();

        $node = new $this->tokenMap[$type](
            $leftNode,
            $rightNode
        );

        $stack->push($node);
    }
}