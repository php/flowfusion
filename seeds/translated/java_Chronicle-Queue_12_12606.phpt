--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/12)
--FILE--
<?php

class PriceUpdate {
    public $timeInMicros;
    public $symbol;
    public $bp;
    public $bq;
    public $ap;
    public $aq;

    public function __construct($timeInMicros, $symbol, $bp, $bq, $ap, $aq) {
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

class PriceWriter {
    private $records = [];

    public function onPrice($timeInMicros, $symbol, $bp, $bq, $ap, $aq) {
        $this->records[] = new PriceUpdate($timeInMicros, $symbol, $bp, $bq, $ap, $aq);
    }

    public function getRecords() {
        return $this->records;
    }
}

class PriceReader {
    private $records;
    private $index = 0;

    public function __construct($records) {
        $this->records = $records;
    }

    public function read() {
        if ($this->index < count($this->records)) {
            return $this->records[$this->index++];
        }
        return null;
    }
}

function main() {
    $pw = new PriceWriter();
    $prices = 200000;
    
    for ($i = 1; $i <= $prices; $i++) {
        $pw->onPrice($i, "symbol", 99.9, $i, 100.1, $i + 1);
    }

    $records = $pw->getRecords();
    $reader = new PriceReader($records);
    
    $start = microtime(true);
    $count = 0;

    while ($record = $reader->read()) {
        $count++;
    }

    $end = microtime(true);
    $averageTime = ($end - $start) * 1e6 / $count;
    
    printf("Average processing time per price: %.2f µs\n", $averageTime);
}

main();
--EXPECT--
