--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/10)
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
    private $data = [];

    public function onPrice($timeInMicros, $symbol, $bp, $bq, $ap, $aq) {
        $priceUpdate = new PriceUpdate($timeInMicros, $symbol, $bp, $bq, $ap, $aq);
        $this->data[] = $priceUpdate->serialize();
    }

    public function getData() {
        return $this->data;
    }
}

class PriceReader {
    private $data;
    private $count = 0;

    public function __construct($data) {
        $this->data = $data;
    }

    public function read() {
        if ($this->count < count($this->data)) {
            $priceUpdate = PriceUpdate::deserialize($this->data[$this->count]);
            $this->count++;
            return $priceUpdate;
        }
        return null;
    }

    public function getCount() {
        return $this->count;
    }
}

$writer = new PriceWriter();
$prices = 200000; // Number of prices

$start = microtime(true);
for ($i = 1; $i <= $prices; $i++) {
    $writer->onPrice($i, 'symbol', 99.9, $i, 100.1, $i + 1);
}
$mid = microtime(true);

$reader = new PriceReader($writer->getData());
while ($reader->read() !== null) {}

$end = microtime(true);
echo sprintf("Took an average of %.2f us to write and %.2f us to read\n",
    (($mid - $start) / $prices) * 1e6, (($end - $mid) / $prices) * 1e6);
?>
--EXPECT--
