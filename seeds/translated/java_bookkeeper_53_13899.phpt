--TEST--
Translated seed (inferredbugs-java: bookkeeper/53)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];

class Ledger {
    private $entries = [];
    private $lastConfirmed = -1;

    public function addEntry($entry) {
        $this->entries[] = $entry;
        $this->lastConfirmed++;
    }

    public function getLastConfirmed() {
        return $this->lastConfirmed;
    }

    public function readEntries($start, $end) {
        return array_slice($this->entries, $start, $end - $start + 1);
    }
}

$lh = new Ledger();

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    $entries[] = $entry;
    $lh->addEntry($entry);
}

$lastConfirmed = $lh->getLastConfirmed();
if ($lastConfirmed !== $numEntriesToWrite - 1) {
    exit("Error: Last confirmed entry mismatch.");
}

$readEntries = $lh->readEntries(0, $lastConfirmed);
for ($i = 0; $i <= $lastConfirmed; $i++) {
    if ($readEntries[$i] !== $entries[$i]) {
        exit("Error: Entry $i mismatch.");
    }
}
--EXPECT--
