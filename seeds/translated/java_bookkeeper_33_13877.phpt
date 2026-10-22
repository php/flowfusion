--TEST--
Translated seed (inferredbugs-java: bookkeeper/33)
--FILE--
<?php

class LedgerHandle {
    private $entries = [];
    private $lastConfirmed = -1;

    public function addEntry($entry) {
        $this->entries[] = $entry;
        $this->lastConfirmed++;
    }

    public function getLastAddConfirmed() {
        return $this->lastConfirmed;
    }

    public function readEntries($start, $end) {
        return array_slice($this->entries, $start, $end - $start + 1);
    }
}

class BookKeeper {
    private $ledgers = [];
    private $ledgerId = 0;

    public function createLedger() {
        $ledger = new LedgerHandle();
        $this->ledgers[$this->ledgerId] = $ledger;
        return $this->ledgerId++;
    }

    public function openLedger($id) {
        return $this->ledgers[$id];
    }
}

class SyncObj {
    public $counter = 0;
}

$bkc = new BookKeeper();
$ledgerId = $bkc->createLedger();
$lh = $bkc->openLedger($ledgerId);
$numEntriesToWrite = 200;

$rng = new Random();
$maxInt = 2147483647;

$sync = new SyncObj();

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = $rng->nextInt($maxInt);
    $lh->addEntry($entry);
    $sync->counter++;
}

$lh = $bkc->openLedger($ledgerId);
$lastConfirmed = $lh->getLastAddConfirmed();
if ($lastConfirmed !== ($numEntriesToWrite - 1)) {
    echo "Error: Last confirmed entry does not match expected value.";
}

$entriesRead = $lh->readEntries(0, $lastConfirmed);
for ($i = 0; $i <= $lastConfirmed; $i++) {
    if ($entriesRead[$i] !== $i) {
        echo "Error: Read entry does not match written entry.";
    }
}
--EXPECT--
