--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/15)
--FILE--
<?php

class PriceUpdate implements Serializable {
    public $timeInMicros;
    public $symbol;
    public $bp;
    public $bq;
    public $ap;
    public $aq;

    public function __construct($timeInMicros = 0, $symbol = '', $bp = 0.0, $bq = 0, $ap = 0.0, $aq = 0) {
        $this->timeInMicros = $timeInMicros;
        $this->symbol = $symbol;
        $this->bp = $bp;
        $this->bq = $bq;
        $this->ap = $ap;
        $this->aq = $aq;
    }

    public function serialize() {
        return serialize([$this->timeInMicros, $this->symbol, $this->bp, $this->bq, $this->ap, $this->aq]);
    }

    public function unserialize($data) {
        list($this->timeInMicros, $this->symbol, $this->bp, $this->bq, $this->ap, $this->aq) = unserialize($data);
    }
}

class PriceWriter {
    private $messages = [];

    public function onPrice($timeInMicros, $symbol, $bp, $bq, $ap, $aq) {
        $pu = new PriceUpdate($timeInMicros, $symbol, $bp, $bq, $ap, $aq);
        $this->messages[] = $pu->serialize();
    }

    public function getMessages() {
        return $this->messages;
    }
}

class PriceReader {
    private $messages = [];
    private $index = 0;

    public function __construct($messages) {
        $this->messages = $messages;
    }

    public function read() {
        if ($this->index < count($this->messages)) {
            $pu = new PriceUpdate();
            $pu->unserialize($this->messages[$this->index]);
            $this->index++;
            return $pu;
        }
        return null;
    }

    public function hasMore() {
        return $this->index < count($this->messages);
    }
}

function main() {
    $prices = 200000;

    $writer = new PriceWriter();
    for ($i = 0; $i < $prices; $i++) {
        $writer->onPrice($i + 1, "symbol", 99.9, $i + 1, 100.1, $i + 2);
    }

    $messages = $writer->getMessages();
    $reader = new PriceReader($messages);

    while ($reader->hasMore()) {
        $priceUpdate = $reader->read();
    }

    echo "Processed $prices price updates.\n";
}

main();
--EXPECT--
