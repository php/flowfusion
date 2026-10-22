--TEST--
Translated seed (inferredbugs-java: bookkeeper/31)
--FILE--
<?php

$ledgerPassword = "aaa";
$ledgerId = null;
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];

class LedgerHandle {
    const INVALID_ENTRY_ID = -1;
    private $entries = [];
    private $lastConfirmed = -1;

    public function createLedger() {
        return $this;
    }

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

    public function getId() {
        return spl_object_id($this);
    }

    public function close() {
        // close logic
    }
}

class Random {
    public function nextInt($max) {
        return rand(0, $max);
    }
}

function testReadWriteSyncSingleClient() {
    global $ledgerPassword, $numEntriesToWrite, $maxInt, $rng;

    $lh = (new LedgerHandle())->createLedger();

    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = pack("I", $rng->nextInt($maxInt));
        $entries[] = $entry;
        $lh->addEntry($entry);
    }

    $lh->close();

    $lh = (new LedgerHandle())->createLedger();
    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $result = $lh->readEntries(0, $lh->getLastAddConfirmed());
        $origEntry = unpack("I", $entries[$i])[1];
        $retrEntry = unpack("I", $result[$i])[1];
        if ($origEntry !== $retrEntry) {
            // handle error
        }
    }

    $lh->close();
}

testReadWriteSyncSingleClient();
--EXPECT--
