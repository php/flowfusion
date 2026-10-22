--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/16)
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
}

class PriceWriter {
    private $data = [];

    public function onPrice($timeInMicros, $symbol, $bp, $bq, $ap, $aq) {
        $update = new PriceUpdate($timeInMicros, $symbol, $bp, $bq, $ap, $aq);
        $this->data[] = $update;
    }

    public function getData() {
        return $this->data;
    }
}

class PriceReader {
    private $data;
    private $index = 0;

    public function __construct($data) {
        $this->data = $data;
    }

    public function read() {
        if ($this->index < count($this->data)) {
            return $this->data[$this->index++];
        }
        return null;
    }

    public function hasNext() {
        return $this->index < count($this->data);
    }
}

function main() {
    $writer = new PriceWriter();
    $prices = 200000;

    for ($i = 1; $i <= $prices; $i++) {
        $writer->onPrice($i, "symbol", 99.9, $i, 100.1, $i + 1);
    }

    $reader = new PriceReader($writer->getData());
    $start = microtime(true);

    while ($reader->hasNext()) {
        $reader->read();
    }

    $end = microtime(true);
    $duration = ($end - $start) * 1e6; // convert to microseconds
    echo sprintf("Processed %d prices in %.2f us\n", $prices, $duration / $prices);
}

main();
--EXPECT--
