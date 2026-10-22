--TEST--
Translated seed (inferredbugs-java: aviator/9)
--FILE--
<?php

class AviatorPattern {
    private $pattern;

    public function __construct($expression) {
        $this->pattern = $expression;
    }

    public function match($other) {
        if (is_string($other)) {
            return preg_match('/' . $this->pattern . '/', $other) === 1;
        }
        return false;
    }

    public function add($other) {
        if (is_string($other)) {
            return $this->pattern . $other;
        }
        return null;
    }
}

$pattern = new AviatorPattern('^test');
$resultMatch = $pattern->match('testString');
$resultAdd = $pattern->add('AddedString');
--EXPECT--
