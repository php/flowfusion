--TEST--
Translated seed (inferredbugs-java: bookkeeper/56)
--FILE--
<?php

class MemoryUsage {
    private $memoryUsage;

    public function __construct() {
        $this->memoryUsage = memory_get_usage();
    }

    public function getMemoryUsage() {
        return memory_get_usage() - $this->memoryUsage;
    }
}

class GarbageCollector {
    public function collect() {
        gc_collect_cycles();
    }
}

$memoryUsage = new MemoryUsage();
$gc = new GarbageCollector();

for ($i = 0; $i < 100000; $i++) {
    $array[] = str_repeat('x', 1024); // Allocate memory
    if ($i % 10000 === 0) {
        $gc->collect(); // Trigger garbage collection periodically
        echo "Memory used after {$i} allocations: " . $memoryUsage->getMemoryUsage() . " bytes\n";
    }
}

$gc->collect(); // Final garbage collection
echo "Final memory usage: " . $memoryUsage->getMemoryUsage() . " bytes\n";
--EXPECT--
