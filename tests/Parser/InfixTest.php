<?php

namespace Equation\Parser;

use Equation\Lexer\Token;
use Equation\Tree\Number as TreeNumber;
use Equation\Tree\Operator\Add as TreeOperatorAdd;
use Equation\Tree\Operator\Multiply as TreeOperatorMultiply;
use Equation\Tree\Operator\Square as TreeOperatorSquare;
use PHPUnit\Framework\TestCase;

class InfixTest extends TestCase
{
    public function testSimpleEquationGeneratesTheCorrectTree()
    {
        $tokens = [
            new Token(Token::T_NUMBER, 1),
            new Token(Token::T_OPERATOR_ADD),
            new Token(Token::T_NUMBER, 1),
        ];

        $expectedTree = new TreeOperatorAdd(
            new TreeNumber(1),
            new TreeNumber(1)
        );

        $parser = $this->createParser($tokens);
        $parsedTree = $parser->parse("");

        $this->assertEquals($expectedTree,$parsedTree);
    }

    public function testEquationWithoutParenthesisGeneratesTheCorrectTree()
    {
        $tokens = [
            new Token(Token::T_NUMBER, 5),
            new Token(Token::T_OPERATOR_ADD),
            new Token(Token::T_NUMBER, 3),
            new Token(Token::T_OPERATOR_MUL),
            new Token(Token::T_NUMBER, 5),
        ];

        $expectedTree = new TreeOperatorAdd(
            new TreeNumber(5),
            new TreeOperatorMultiply(
                new TreeNumber(3),
                new TreeNumber(5),
            )
        );

        $parser = $this->createParser($tokens);
        $parsedTree = $parser->parse("");

        $this->assertEquals($expectedTree,$parsedTree);
    }

    public function testEquationWithParenthesisGeneratesTheCorrectTree()
    {
        $tokens = [
            new Token(Token::T_L_PAREN),
            new Token(Token::T_NUMBER, 5),
            new Token(Token::T_OPERATOR_ADD),
            new Token(Token::T_NUMBER, 3),
            new Token(Token::T_R_PAREN),
            new Token(Token::T_OPERATOR_MUL),
            new Token(Token::T_NUMBER, 5),
        ];

        $expectedTree = new TreeOperatorMultiply(
            new TreeOperatorAdd(
                new TreeNumber(5),
                new TreeNumber(3),
            ),
            new TreeNumber(5),
        );
        
        $parser = $this->createParser($tokens);
        $parsedTree = $parser->parse("");

        $this->assertEquals($expectedTree,$parsedTree);
    }

    public function testExponentsWithoutParenthesisGeneratesTheCorrectTree()
    {
        $tokens = [
            new Token(Token::T_NUMBER, 2),
            new Token(Token::T_OPERATOR_POW),
            new Token(Token::T_NUMBER, 1),
            new Token(Token::T_OPERATOR_ADD),
            new Token(Token::T_NUMBER, 3),
        ];

        $expectedTree = new TreeOperatorAdd(
            new TreeOperatorSquare(
                new TreeNumber(2),
                new TreeNumber(1),
            ),
            new TreeNumber(3)
        );

        $parser = $this->createParser($tokens);
        $parsedTree = $parser->parse("");

        $this->assertEquals($expectedTree,$parsedTree);
    }

    public function testExponentsWithParenthesisGeneratesTheCorrectTree()
    {
        $tokens = [
            new Token(Token::T_NUMBER, 2),
            new Token(Token::T_OPERATOR_POW),
            new Token(Token::T_L_PAREN),
            new Token(Token::T_NUMBER, 1),
            new Token(Token::T_OPERATOR_ADD),
            new Token(Token::T_NUMBER, 3),
            new Token(Token::T_R_PAREN),
        ];

        $expectedTree = new TreeOperatorSquare(
            new TreeNumber(2),
            new TreeOperatorAdd(
                new TreeNumber(1),
                new TreeNumber(3),
            )
        );

        $parser = $this->createParser($tokens);
        $parsedTree = $parser->parse("");

        $this->assertEquals($expectedTree,$parsedTree);
    }

    public function createParser(array $tokens) : Infix
    {
        return new \Equation\Parser\Infix(
            $lexer = new \Equation\Mocks\Lexer($tokens),
            new \SplStack(),
            new \SplStack()
        );
    }
}