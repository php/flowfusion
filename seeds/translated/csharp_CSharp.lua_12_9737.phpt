--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/12)
--FILE--
<?php

class Example {
    private $value;

    public function __construct($value) {
        if ($value < 0) {
            throw new InvalidArgumentException("Negative value");
        }
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        if ($value < 0) {
            throw new InvalidArgumentException("Negative value");
        }
        $this->value = $value;
    }
}

function triggerBug() {
    $example = new Example(10);
    $example->setValue(-5); // This should trigger the error
}

triggerBug();
?>
--EXPECT--
