--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/2)
--FILE--
<?php

class Worker {
    private $applicationName;
    private $recordProcessorFactory;
    private $shutdown = false;
    private $shardInfoShardConsumerMap = [];

    public function __construct($applicationName, $recordProcessorFactory) {
        $this->applicationName = $applicationName;
        $this->recordProcessorFactory = $recordProcessorFactory;
    }

    public function run() {
        $this->initialize();
        while (!$this->shutdown) {
            $shardInfo = $this->getShardInfoForAssignments();
            foreach ($shardInfo as $info) {
                if (!isset($this->shardInfoShardConsumerMap[$info])) {
                    $this->shardInfoShardConsumerMap[$info] = $this->createOrGetShardConsumer($info);
                }
                $this->shardInfoShardConsumerMap[$info]->consumeShard();
            }
            usleep(100000); // simulate idle time
        }
    }

    private function initialize() {
        // Initialization logic
    }

    private function getShardInfoForAssignments() {
        // Return a simulated list of shard info
        return ['shard1', 'shard2'];
    }

    private function createOrGetShardConsumer($shardInfo) {
        return new ShardConsumer($shardInfo, $this->recordProcessorFactory);
    }

    public function shutdown() {
        $this->shutdown = true;
    }
}

class ShardConsumer {
    private $shardInfo;
    private $recordProcessor;

    public function __construct($shardInfo, $recordProcessorFactory) {
        $this->shardInfo = $shardInfo;
        $this->recordProcessor = $recordProcessorFactory->createProcessor();
    }

    public function consumeShard() {
        // Simulate consuming a shard
        echo "Consuming shard: " . $this->shardInfo . "\n";
    }
}

class RecordProcessorFactory {
    public function createProcessor() {
        return new RecordProcessor();
    }
}

class RecordProcessor {
    // Implementation of record processing
}

$worker = new Worker("DemoApplication", new RecordProcessorFactory());
$worker->run();
--EXPECT--
