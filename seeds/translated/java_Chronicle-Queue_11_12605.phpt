--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/11)
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
        $priceUpdate = new PriceUpdate($timeInMicros, $symbol, $bp, $bq, $ap, $aq);
        $this->data[] = serialize($priceUpdate);
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
            $priceUpdate = unserialize($this->data[$this->index]);
            $this->index++;
            return $priceUpdate;
        }
        return null;
    }
}

function main() {
    $writer = new PriceWriter();
    
    $start = microtime(true);
    $prices = 200000;
    for ($i = 1; $i <= $prices; $i++) {
        $writer->onPrice($i, 'symbol', 99.9, $i, 100.1, $i + 1);
    }
    $mid = microtime(true);

    $reader = new PriceReader($writer->getData());
    while ($reader->read() !== null) {}

    $end = microtime(true);

    printf("Took an average of %.2f us to write and %.2f us to read\n",
        (($mid - $start) / $prices) * 1e6, (($end - $mid) / $prices) * 1e6);
}

main();
--EXPECT--
