--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/3)
--FILE--
<?php

$baseDir = sys_get_temp_dir();
$srcBasePath = $baseDir . '/IPCT.testOverTCP.source';
$snkBasePath = $baseDir . '/IPCT.testOverTCP.sink';
$messageCount = 2 * 1000 * 1000;

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
    private $excerpt;

    public function __construct($excerpt) {
        $this->excerpt = $excerpt;
    }

    public function onPrice($timeInMicros, $symbol, $bp, $bq, $ap, $aq) {
        $this->excerpt[] = new PriceUpdate($timeInMicros, $symbol, $bp, $bq, $ap, $aq);
    }
}

class PriceReader {
    private $excerpt;
    private $listener;

    public function __construct(&$excerpt, $listener) {
        $this->excerpt = &$excerpt;
        $this->listener = $listener;
    }

    public function read() {
        if (empty($this->excerpt)) return false;
        $update = array_shift($this->excerpt);
        ($this->listener)($update);
        return true;
    }
}

$sourceExcerpt = [];
$writer = new PriceWriter($sourceExcerpt);

for ($i = 1; $i <= $messageCount; $i++) {
    $writer->onPrice($i, "symbol", 99.9, $i, 100.1, $i + 1);
}

$count = 0;
$reader = new PriceReader($sourceExcerpt, function($priceUpdate) use (&$count) {
    $count++;
});

$start = microtime(true);
while (!empty($sourceExcerpt)) {
    $reader->read();
}
$end = microtime(true);

$timeTaken = ($end - $start) * 1e6;
$averageTime = $timeTaken / $messageCount;

echo sprintf("Average time per message: %.2f µs\n", $averageTime);
--EXPECT--
