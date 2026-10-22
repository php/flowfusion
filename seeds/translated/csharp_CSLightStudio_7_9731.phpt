--TEST--
Translated seed (inferredbugs-csharp: CSLightStudio/7)
--FILE--
<?php

class CLS_Expression_LoopDowhile {
    private $lineBegin;
    private $lineEnd;
    private $listParam = [];
    private $tokenBegin;
    private $tokenEnd;

    public function __construct(int $tbegin, int $tend, int $lbegin, int $lend) {
        $this->tokenBegin = $tbegin;
        $this->tokenEnd = $tend;
        $this->lineBegin = $lbegin;
        $this->lineEnd = $lend;
    }

    public function addExpression($expression) {
        $this->listParam[] = $expression;
    }

    public function computeValue($content) {
        $expr_while = $this->listParam[1];
        $expr_block = $this->listParam[0];

        $v = null;
        do {
            if ($expr_block !== null) {
                if ($expr_block instanceof CLS_Expression_Block) {
                    $v = $expr_block->computeValue($content);
                    if ($v !== null && $v->breakBlock > 1) break;
                } else {
                    $v = $expr_block->computeValue($content);
                    if ($v !== null && $v->breakBlock > 1) break;
                }
            }
        } while ($expr_while->computeValue($content)->value);
        
        return $v;
    }
}

class CLS_Expression_Block {
    public function computeValue($content) {
        // Simulate some operations and return a value
        $result = new stdClass();
        $result->breakBlock = 0; // Simulates continuing the loop
        return $result;
    }
}

class CLS_Expression_While {
    public function computeValue($content) {
        // Simulate a condition
        $result = new stdClass();
        $result->value = true; // Simulates a true condition
        return $result;
    }
}

function main() {
    $loop = new CLS_Expression_LoopDowhile(1, 2, 3, 4);
    $loop->addExpression(new CLS_Expression_Block());
    $loop->addExpression(new CLS_Expression_While());
    
    $content = new stdClass(); // Placeholder for content
    $loop->computeValue($content);
}

main();
--EXPECT--
