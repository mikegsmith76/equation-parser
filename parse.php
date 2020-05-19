<?php

require_once "./vendor/autoload.php";

use Equation\Printer\Infix as InfixPrinter;
use Equation\Printer\Prefix as PrefixPrinter;
use Equation\Printer\Postfix as PostfixPrinter;

// $equation = "3 + 5 * 4 + 8";
$equation = "( ( 3 + ( 5 * 4 ) ) + 8 )";

$parser = new Equation\Parser\Infix(
    new Equation\Lexer(),
    new SplStack(),
    new SplStack()
);

$equationTree = $parser->parse($equation);

print "Starting Equation: " . $equation . PHP_EOL;

print (new InfixPrinter)->print($equationTree) . PHP_EOL;
print (new PrefixPrinter)->print($equationTree) . PHP_EOL;
print (new PostfixPrinter)->print($equationTree) . PHP_EOL;