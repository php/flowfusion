--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/9)
--FILE--
<?php

class PriceUpdate {
    public $timeInMicros;
    public $symbol;
    public $bp;
    public $bq;
    public $ap;
    public $aq;

    public function __construct($timeInMicros = 0, $symbol = "", $bp = 0.0, $bq = 0, $ap = 0.0, $aq = 0) {
        $this->timeInMicros = $timeInMicros;
        $this->symbol = $symbol;
        $this->bp = $bp;
        $this->bq = $bq;
        $this->ap = $ap;
        $this->aq = $aq;
    }

    public function serialize() {
        return serialize($this);
    }

    public static function deserialize($data) {
        return unserialize($data);
    }
}

function testSerializationPerformance() {
    $bytes = [];
    $start = microtime(true);
    $prices = 200000;
    for ($i = 0; $i < $prices; $i++) {
        $pu = new PriceUpdate(1 + $i, "symbol", 99.9, $i + 1, 100.1, $i + 2);
        $bytes[] = $pu->serialize();
    }

    $mid = microtime(true);
    foreach ($bytes as $bs) {
        PriceUpdate::deserialize($bs);
    }

    $end = microtime(true);
    printf("Took an average of %.1f us to write and %.1f us to read\n",
        ($mid - $start) / $prices * 1e6, ($end - $mid) / $prices * 1e6);
}

testSerializationPerformance();
--EXPECT--
