--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/30)
--FILE--
<?php

class SingleChronicleQueue {
    private const SUFFIX = '.cq4';
    private const TIMEOUT = 10000;
    private $epoch;
    private $path;
    private $files;

    public function __construct($path, $epoch) {
        $this->path = $path;
        $this->epoch = $epoch;
        $this->files = glob($path . '/*' . self::SUFFIX);
    }

    public function firstIndex() {
        $firstCycle = $this->firstCycle();
        if ($firstCycle === -1) {
            return -1;
        }
        // Simulated sequence number for demonstration
        return $this->index($firstCycle, 1);
    }

    private function firstCycle() {
        if (empty($this->files)) {
            return -1;
        }

        $firstDate = PHP_INT_MAX;

        foreach ($this->files as $file) {
            $name = basename($file, self::SUFFIX);
            $date = strtotime($name);
            if ($date < $firstDate) {
                $firstDate = $date;
            }
        }

        return $firstDate === PHP_INT_MAX ? -1 : $firstDate;
    }

    public function lastIndex() {
        $lastCycle = $this->lastCycle();
        if ($lastCycle === -1) {
            return -1;
        }
        // Simulated sequence number for demonstration
        return $this->index($lastCycle, 1);
    }

    private function lastCycle() {
        if (empty($this->files)) {
            return -1;
        }

        $lastDate = PHP_INT_MIN;

        foreach ($this->files as $file) {
            $name = basename($file, self::SUFFIX);
            $date = strtotime($name);
            if ($date > $lastDate) {
                $lastDate = $date;
            }
        }

        return $lastDate === PHP_INT_MIN ? -1 : $lastDate;
    }

    private function index($cycle, $sequenceNumber) {
        // Simple index generation logic
        return ($cycle * 1000) + $sequenceNumber;
    }
}

$queue = new SingleChronicleQueue('/path/to/your/directory', time());
$firstIndex = $queue->firstIndex();
$lastIndex = $queue->lastIndex();
--EXPECT--
