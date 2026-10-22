--TEST--
Translated seed (inferredbugs-java: bookkeeper/15)
--FILE--
<?php

class LedgerHandle {
    const INVALID_ENTRY_ID = -1;
    private $lastAddPushed = 0;
    private $lastAddConfirmed = 0;
    private $length = 0;
    private $opCounterSem;

    public function __construct($throttling) {
        $this->opCounterSem = $throttling;
    }

    public function addEntry($data) {
        if ($this->opCounterSem <= 0) {
            throw new Exception("Throttling limit exceeded");
        }
        $this->opCounterSem--;
        $this->lastAddPushed++;
        $this->length += strlen($data);
        $this->opCounterSem++;
        return $this->lastAddPushed;
    }

    public function close() {
        // Simulate closing the ledger
        $this->lastAddConfirmed = $this->lastAddPushed;
    }

    public function readLastConfirmed() {
        return $this->lastAddConfirmed;
    }

    public function getLength() {
        return $this->length;
    }
}

$ledger = new LedgerHandle(10);
$ledger->addEntry("data1");
$ledger->addEntry("data2");
$ledger->close();

echo "Last Confirmed Entry ID: " . $ledger->readLastConfirmed() . "\n";
echo "Length of Ledger: " . $ledger->getLength() . "\n";
--EXPECT--
