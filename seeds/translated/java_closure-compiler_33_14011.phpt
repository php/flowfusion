--TEST--
Translated seed (inferredbugs-java: closure-compiler/33)
--FILE--
<?php

class Compiler {
    public function compile($input) {
        if (strlen($input) > 100) {
            return substr($input, 0, 100);
        }
        return $input;
    }
}

$compiler = new Compiler();
$input = str_repeat('A', 150);  // Trigger an edge case
$output = $compiler->compile($input);
echo $output;
--EXPECT--
