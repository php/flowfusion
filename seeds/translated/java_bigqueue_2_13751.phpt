--TEST--
Translated seed (inferredbugs-java: bigqueue/2)
--FILE--
<?php

class BigQueue {
    private $innerArray;
    private $queueFrontIndex = 0;
    private $queueFrontIndexPageSize = 8;
    private $queueFrontIndexPageFolder = "front_index";
    private $queueFrontWriteLock = false;

    public function __construct($queueDir, $queueName) {
        $this->innerArray = []; 
    }

    public function isEmpty() {
        return $this->queueFrontIndex === count($this->innerArray);
    }

    public function enqueue($data) {
        $this->innerArray[] = $data;
    }

    public function dequeue() {
        if ($this->isEmpty()) {
            return null;
        }
        $data = $this->innerArray[$this->queueFrontIndex];
        $this->queueFrontIndex++;
        if ($this->queueFrontIndex === count($this->innerArray)) {
            $this->queueFrontIndex = 0; // wrap around
        }
        return $data;
    }

    public function removeAll() {
        $this->innerArray = [];
        $this->queueFrontIndex = 0;
    }

    public function peek() {
        if ($this->isEmpty()) {
            return null;
        }
        return $this->innerArray[$this->queueFrontIndex];
    }
}

$queue = new BigQueue("/tmp", "testQueue");
$queue->enqueue("data1");
$queue->enqueue("data2");
echo $queue->dequeue(); // Outputs: data1
echo $queue->dequeue(); // Outputs: data2
echo $queue->dequeue(); // Outputs: nothing (null)
$queue->removeAll();
echo $queue->isEmpty() ? "Queue is empty" : "Queue is not empty"; // Outputs: Queue is empty
--EXPECT--
