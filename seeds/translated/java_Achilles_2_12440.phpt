--TEST--
Translated seed (inferredbugs-java: Achilles/2)
--FILE--
<?php

class Counter {
    private $value = 0;

    public function increment($amount) {
        $this->value += $amount;
    }

    public function decrement($amount) {
        $this->value -= $amount;
    }

    public function getValue() {
        return $this->value;
    }
}

function main() {
    $counter = new Counter();
    
    $counter->increment(100);
    $counter->decrement(50);
    $counter->increment(25);
    $counter->decrement(75);
    
    echo $counter->getValue();
}

main();
--EXPECT--
