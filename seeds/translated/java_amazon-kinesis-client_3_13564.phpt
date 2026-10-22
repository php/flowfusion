--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/3)
--FILE--
<?php

class Worker {
    private $shutdown = false;
    private $shardInfoShardConsumerMap = [];
    private $idleTimeInMilliseconds = 1000;

    public function run() {
        while (!$this->shouldShutdown()) {
            $this->runProcessLoop();
        }
        $this->finalShutdown();
    }

    private function runProcessLoop() {
        try {
            $assignedShards = $this->getShardInfoForAssignments();
            foreach ($assignedShards as $shardInfo) {
                $shardConsumer = $this->createOrGetShardConsumer($shardInfo);
                if ($shardConsumer->isShutdown()) {
                    // Handle completed shard
                } else {
                    $shardConsumer->consumeShard();
                }
            }
            usleep($this->idleTimeInMilliseconds * 1000);
        } catch (Exception $e) {
            usleep($this->idleTimeInMilliseconds * 1000);
        }
    }

    private function getShardInfoForAssignments() {
        return ['shard1', 'shard2', 'shard3'];
    }

    private function createOrGetShardConsumer($shardInfo) {
        if (!isset($this->shardInfoShardConsumerMap[$shardInfo])) {
            $this->shardInfoShardConsumerMap[$shardInfo] = new ShardConsumer($shardInfo);
        }
        return $this->shardInfoShardConsumerMap[$shardInfo];
    }

    private function shouldShutdown() {
        return $this->shutdown;
    }

    public function shutdown() {
        $this->shutdown = true;
    }

    private function finalShutdown() {
        // Cleanup resources
    }
}

class ShardConsumer {
    private $shardInfo;
    private $isShutdown = false;

    public function __construct($shardInfo) {
        $this->shardInfo = $shardInfo;
    }

    public function isShutdown() {
        return $this->isShutdown;
    }

    public function consumeShard() {
        // Consume shard logic
    }
}

$worker = new Worker();
$worker->run();
--EXPECT--
