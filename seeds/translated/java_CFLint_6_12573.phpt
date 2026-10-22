--TEST--
Translated seed (inferredbugs-java: CFLint/6)
--FILE--
<?php

class BugReporter {
    private $bugs = [];

    public function addBug($message) {
        $this->bugs[] = $message;
    }

    public function report() {
        foreach ($this->bugs as $bug) {
            echo $bug . PHP_EOL;
        }
    }
}

function processArray($arr, $expected) {
    $reporter = new BugReporter();
    $sum = 0;

    foreach ($arr as $value) {
        if (!is_int($value)) {
            $reporter->addBug("Non-integer value found: $value");
        }
        $sum += $value;
    }

    if ($sum !== $expected) {
        $reporter->addBug("Expected sum: $expected, but got: $sum");
    }

    $reporter->report();
}

function main() {
    $testArray = [1, 2, "three", 4, 5]; // This includes a bug: "three" is not an integer
    $expectedSum = 15; // The expected sum (1 + 2 + 3 + 4 + 5 = 15)
    
    processArray($testArray, $expectedSum);
}

main();
--EXPECT--
