--TEST--
Translated seed (inferredbugs-java: bookkeeper/38)
--FILE--
<?php

$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$ledgerPassword = "aaa";
$ledgerId = 1; // Simulate ledger ID
$syncCounter = 0;

class LedgerEntry {
    private $entry;

    public function __construct($entry) {
        $this->entry = $entry;
    }

    public function getEntry() {
        return $this->entry;
    }
}

class LedgerHandle {
    private $entries = [];
    private $lastEntryId = -1;

    public function addEntry($entry) {
        $this->entries[] = new LedgerEntry($entry);
        $this->lastEntryId++;
    }

    public function readEntries($start, $end) {
        return array_slice($this->entries, $start, $end - $start + 1);
    }

    public function getLastAddConfirmed() {
        return $this->lastEntryId;
    }
}

function randomInt($max) {
    return rand(0, $max);
}

$data = [];

$lh = new LedgerHandle();
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = pack('N', randomInt($maxInt));
    $data[] = $entry;
    $lh->addEntry($entry);
}

$lastConfirmed = $lh->getLastAddConfirmed();

$readEntries = $lh->readEntries(0, $lastConfirmed);
foreach ($readEntries as $i => $entry) {
    $origEntry = unpack('N', $data[$i])[1];
    $retrEntry = unpack('N', $entry->getEntry())[1];
}
--EXPECT--
