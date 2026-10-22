--TEST--
Translated seed (inferredbugs-java: YCSB/6)
--FILE--
<?php

class AcknowledgedCounterGenerator {
    private const WINDOW_SIZE = 10000;
    private $window;
    private $limit;

    public function __construct($countstart) {
        $this->window = array_fill(0, self::WINDOW_SIZE, false);
        $this->limit = $countstart - 1;
    }

    public function lastInt() {
        return $this->limit;
    }

    public function acknowledge($value) {
        if ($value > $this->limit + self::WINDOW_SIZE) {
            throw new Exception("This should be a different exception.");
        }

        $this->window[$value % self::WINDOW_SIZE] = true;

        $index = $this->limit + 1;

        while ($index <= $value) {
            $slot = $index % self::WINDOW_SIZE;

            if (!$this->window[$slot]) {
                break;
            }

            $this->window[$slot] = false;
            $index++;
        }

        $this->limit = $index - 1;
    }
}

$counter = new AcknowledgedCounterGenerator(0);
$counter->acknowledge(1);
$counter->acknowledge(2);
$counter->acknowledge(10001);
echo $counter->lastInt();
--EXPECT--
