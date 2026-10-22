--TEST--
Translated seed (inferredbugs-java: bigqueue/1)
--FILE--
<?php

class BigQueueImpl {
    private $innerArray;
    private $queueFrontIndex = 0;
    private $queueFrontWriteLock = false;

    const QUEUE_FRONT_INDEX_ITEM_LENGTH_BITS = 3;
    const QUEUE_FRONT_INDEX_PAGE_SIZE = 1 << self::QUEUE_FRONT_INDEX_ITEM_LENGTH_BITS;

    public function __construct($queueDir, $queueName, $pageSize = 4096) {
        $this->innerArray = new SplFixedArray($pageSize);
    }

    public function isEmpty() {
        return $this->queueFrontIndex === 0;
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
        return $data;
    }

    public function size() {
        return count($this->innerArray) - $this->queueFrontIndex;
    }
}

$queue = new BigQueueImpl('/path/to/queue', 'testQueue');
$queue->enqueue('data1');
$queue->enqueue('data2');
$queue->dequeue();
$queue->dequeue();
--EXPECT--
