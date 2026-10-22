--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/1)
--FILE--
<?php
class Worker {
    private $shutdown = false;
    private $shardInfoShardConsumerMap = [];

    public function run() {
        while (!$this->shutdown) {
            $assignedShards = $this->getShardInfoForAssignments();
            $foundCompletedShard = false;

            foreach ($assignedShards as $shardInfo) {
                $shardConsumer = $this->createOrGetShardConsumer($shardInfo);
                if ($shardConsumer['shutdown'] && $shardConsumer['reason'] === 'TERMINATE') {
                    $foundCompletedShard = true;
                } else {
                    $this->consumeShard($shardConsumer);
                }
                $this->shardInfoShardConsumerMap[$shardInfo] = $shardConsumer;
            }

            if ($foundCompletedShard) {
                $this->syncShardAndLeaseInfo();
            }

            usleep(100000); // Simulate idle time
        }
        $this->finalShutdown();
    }

    private function getShardInfoForAssignments() {
        return array_keys($this->shardInfoShardConsumerMap);
    }

    private function createOrGetShardConsumer($shardInfo) {
        if (!isset($this->shardInfoShardConsumerMap[$shardInfo])) {
            $this->shardInfoShardConsumerMap[$shardInfo] = [
                'shutdown' => false,
                'reason' => null,
                'data' => "Data for $shardInfo"
            ];
        }
        return $this->shardInfoShardConsumerMap[$shardInfo];
    }

    private function consumeShard($shardConsumer) {
        // Simulate consuming shard data
        echo "Consuming " . $shardConsumer['data'] . PHP_EOL;
    }

    private function syncShardAndLeaseInfo() {
        echo "Syncing shard and lease info..." . PHP_EOL;
    }

    public function shutdown() {
        $this->shutdown = true;
    }

    private function finalShutdown() {
        echo "Final shutdown procedures..." . PHP_EOL;
    }
}

$worker = new Worker();
$worker->run();
--EXPECT--
