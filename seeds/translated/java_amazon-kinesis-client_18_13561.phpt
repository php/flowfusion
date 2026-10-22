--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/18)
--FILE--
<?php

class RecordsRetrieved {
    private $recordsInput;

    public function __construct($recordsInput) {
        $this->recordsInput = $recordsInput;
    }

    public function processRecordsInput() {
        return $this->recordsInput;
    }
}

class ShardConsumer {
    public function handleInput($input, $subscription) {
        // Simulate processing the input
    }
}

class Subscriber {
    private $shardConsumer;
    private $recordsRetrieved;

    public function __construct($shardConsumer, $recordsRetrieved) {
        $this->shardConsumer = $shardConsumer;
        $this->recordsRetrieved = $recordsRetrieved;
    }

    public function start() {
        $this->shardConsumer->handleInput($this->recordsRetrieved->processRecordsInput(), null);
    }
}

class TestPublisher {
    private $responses = [];
    private $requested = 0;

    public function add($response) {
        $this->responses[] = $response;
        $this->send();
    }

    public function send() {
        while ($this->requested > 0 && !empty($this->responses)) {
            $response = array_shift($this->responses);
            $this->requested--;
        }
    }

    public function request($n) {
        $this->requested += $n;
        $this->send();
    }
}

$shardConsumer = new ShardConsumer();
$recordsRetrieved = new RecordsRetrieved("input data");
$subscriber = new Subscriber($shardConsumer, $recordsRetrieved);
$publisher = new TestPublisher();

$publisher->add($recordsRetrieved);
$subscriber->start();
$publisher->request(1);
$publisher->add(new RecordsRetrieved("terminal marker"));
$publisher->send();
--EXPECT--
