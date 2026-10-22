--TEST--
Translated seed (inferredbugs-java: bookkeeper/47)
--FILE--
<?php

class ServerStats {
    private static $instance;
    private $packetsSent = 0;
    private $packetsReceived = 0;
    private $maxLatency = 0;
    private $minLatency = PHP_INT_MAX;
    private $totalLatency = 0;
    private $count = 0;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ServerStats();
        }
        return self::$instance;
    }

    public function getMinLatency() {
        return ($this->minLatency === PHP_INT_MAX) ? 0 : $this->minLatency;
    }

    public function getAvgLatency() {
        if ($this->count != 0) {
            return intdiv($this->totalLatency, $this->count);
        }
        return 0;
    }

    public function getMaxLatency() {
        return $this->maxLatency;
    }

    public function resetLatency() {
        $this->totalLatency = $this->count = $this->maxLatency = 0;
        $this->minLatency = PHP_INT_MAX;
    }

    public function updateLatency($requestCreateTime) {
        $latency = microtime(true) * 1000 - $requestCreateTime;
        $this->totalLatency += $latency;
        $this->count++;
        if ($latency < $this->minLatency) {
            $this->minLatency = $latency;
        }
        if ($latency > $this->maxLatency) {
            $this->maxLatency = $latency;
        }
    }

    public function incrementPacketsReceived() {
        $this->packetsReceived++;
    }

    public function incrementPacketsSent() {
        $this->packetsSent++;
    }

    public function getPacketsReceived() {
        return $this->packetsReceived;
    }

    public function getPacketsSent() {
        return $this->packetsSent;
    }
}

$serverStats = ServerStats::getInstance();
$serverStats->incrementPacketsSent();
$serverStats->incrementPacketsReceived();
$serverStats->updateLatency(microtime(true) * 1000 - 10);
$serverStats->updateLatency(microtime(true) * 1000 - 20);
$serverStats->updateLatency(microtime(true) * 1000 - 15);
--EXPECT--
