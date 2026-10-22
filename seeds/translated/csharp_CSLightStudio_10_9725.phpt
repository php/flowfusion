--TEST--
Translated seed (inferredbugs-csharp: CSLightStudio/10)
--FILE--
<?php

class Token {
    public $text;
    public $type;
    
    public function __construct($text, $type) {
        $this->text = $text;
        $this->type = $type;
    }
}

class CLS_Expression {
    // Class representing an expression
}

class CLS_Expression_Math extends CLS_Expression {
    public $listParam = [];
    public $mathop;

    public function __construct($left, $right, $mathop) {
        $this->listParam[] = $left;
        $this->listParam[] = $right;
        $this->mathop = $mathop;
    }
}

function Compiler_Expression_Math($tlist, $pos, $posend) {
    if ($pos >= $posend) {
        return null;
    }

    $oppos = -1;
    // Example of finding an operator; you can replace this part with your logic
    for ($i = $pos; $i < $posend; $i++) {
        if ($tlist[$i]->text === '+' || $tlist[$i]->text === '-') {
            $oppos = $i;
            break;
        }
    }

    if ($oppos < 0) {
        return null;
    }

    $left = Compiler_Expression_Math($tlist, $pos, $oppos);
    $right = Compiler_Expression_Math($tlist, $oppos + 1, $posend);

    if ($left !== null && $right !== null) {
        return new CLS_Expression_Math($left, $right, $tlist[$oppos]->text);
    }

    return null;
}

function run() {
    $tokens = [
        new Token('3', 'NUMBER'),
        new Token('+', 'OPERATOR'),
        new Token('5', 'NUMBER')
    ];

    $expression = Compiler_Expression_Math($tokens, 0, count($tokens));

    if ($expression !== null) {
        echo "Expression built successfully with operator: " . $expression->mathop . "\n";
    } else {
        echo "Failed to build expression.\n";
    }
}

run();
--EXPECT--
