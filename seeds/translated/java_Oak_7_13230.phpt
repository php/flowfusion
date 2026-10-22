--TEST--
Translated seed (inferredbugs-java: Oak/7)
--FILE--
<?php

class FakeMap {
    private $store = [];

    public function put($key, $value) {
        $this->store[$key] = $value;
    }

    public function get($key) {
        return $this->store[$key];
    }

    public function entrySet() {
        return new ArrayIterator($this->store);
    }

    public function values() {
        return new ArrayIterator(array_values($this->store));
    }
}

$oak = new FakeMap();

$oak->put("AAAAAAA", "h");
$oak->put("ZZZZZZZ", "h");

$stringBuilder = "";
for ($i = 0; $i < 100; $i++) {
    $stringBuilder .= $i;
}
$longValue = $stringBuilder;

$semaphore1 = 0;
$semaphore2 = 0;

$iteratorThread = function() use (&$semaphore1, &$semaphore2, $oak) {
    $iterator = $oak->entrySet();
    $valueIterator = $oak->values();

    $entry = $iterator->current();
    $currentValue = $valueIterator->current();
    if ($entry[0] !== "AAAAAAA" || $currentValue !== "h") {
        exit(1);
    }
    $semaphore1++;

    while ($semaphore2 === 0) {
        usleep(1000);
    }

    $iterator->next();
    $valueIterator->next();
    $entry = $iterator->current();
    $currentValue = $valueIterator->current();
    if ($entry[0] !== "ZZZZZZZ" || $currentValue !== $longValue) {
        exit(1);
    }
};

$modifyThread = function() use (&$semaphore1, &$semaphore2, $oak, $longValue) {
    while ($semaphore1 === 0) {
        usleep(1000);
    }
    $oak->put("ZZZZZZZ", $longValue);
    $semaphore2++;
};

$iteratorThread();
$modifyThread();
--EXPECT--
