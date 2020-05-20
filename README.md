# Equation Parser

## Overview
Basic parser to transform a simple maths equation into a tree representation. Currently provides support for parsing Prefix and Infix representations.

## Requirements

None

## Examples

Parsing an infix expression into a tree representation

```php
$equation = "3+5*4+8";

$parser = new Equation\Parser\Infix(
    new Equation\Lexer\Regex(),
    new SplStack(),
    new SplStack()
);

$tree = $parser->parse($equation);
```

Printing a parsed tree into an alternative notation

```php
print (new Equation\Printer\Prefix)->print($equationTree);
```