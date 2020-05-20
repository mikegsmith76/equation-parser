<?php

require_once __DIR__ . "/../vendor/autoload.php";

$equation = "3+5+4+8";

$parser = new Equation\Parser\Infix(
    new Equation\Lexer\Regex(),
    new SplStack(),
    new SplStack()
);

$equationTree = $parser->parse($equation);

print "Starting Equation: " . $equation . PHP_EOL;

print (new Equation\Printer\Infix)->print($equationTree) . PHP_EOL;
print (new Equation\Printer\Prefix)->print($equationTree) . PHP_EOL;
print (new Equation\Printer\Postfix)->print($equationTree) . PHP_EOL;