--TEST--
Translated seed (inferredbugs-java: aviator/1)
--FILE--
<?php

class AviatorPattern {
    private $pattern;

    public function __construct($expression) {
        $this->pattern = $expression;
    }

    public function match($string) {
        return preg_match('/^' . $this->pattern . '$/', $string) === 1;
    }

    public function add($other) {
        if (is_string($other)) {
            return $this->pattern . $other;
        } 
        return null;
    }
}

$pattern = new AviatorPattern('foo');
$result1 = $pattern->match('foo'); // should be true
$result2 = $pattern->match('bar'); // should be false
$concatenated = $pattern->add('bar');

echo $result1 ? 'Match: true' : 'Match: false';
echo "\n";
echo $result2 ? 'Match: true' : 'Match: false';
echo "\n";
echo 'Concatenated: ' . $concatenated;
--EXPECT--
