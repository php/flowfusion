--TEST--
Translated seed (inferredbugs-java: YCSB/9)
--FILE--
<?php
class BugPatternTest {
    private $data = [];

    public function run() {
        $key = "large_number";
        $this->data[$key] = PHP_INT_MAX; // Set to max integer value
        $this->data[$key]++; // Trigger integer overflow

        // Output the value which should be PHP_INT_MIN if overflow occurs
        echo $this->data[$key];
    }
}

$test = new BugPatternTest();
$test->run();
--EXPECT--
