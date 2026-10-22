--TEST--
Translated seed (inferredbugs-java: bookkeeper/51)
--FILE--
<?php
$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];

class SyncObj {
    public $counter;
    public $value;
    public $lastConfirmed = -1;

    public function __construct() {
        $this->counter = 0;
        $this->value = false;
    }

    public function setReturnCode($rc) {
        $this->returnCode = $rc;
    }

    public function getReturnCode() {
        return $this->returnCode;
    }

    public function setLedgerEntries($ls) {
        $this->ls = $ls;
    }

    public function getLedgerEntries() {
        return $this->ls;
    }
}

function wait($sync) {
    while ($sync->counter < 200) {
        usleep(100);
    }
}

function testReadWriteAsyncSingleClient() {
    global $ledgerPassword, $numEntriesToWrite, $maxInt, $rng, $entries, $entriesSize;
    $sync = new SyncObj();

    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = pack('N', $rng->nextInt($maxInt));
        $entries[] = $entry;
        $entriesSize[] = strlen($entry);
        // Simulating asyncAddEntry
        $sync->counter++;
    }

    wait($sync);
    $lastConfirmed = $sync->counter - 1;

    $syncValue = true; // simulating read complete
    $sync->value = $syncValue;

    if ($sync->value) {
        $i = 0;
        foreach ($entries as $entry) {
            $retrEntry = unpack('N', $entry)[1];
            if ($retrEntry !== unpack('N', $entries[$i])[1]) {
                throw new Exception("Entry $i mismatch");
            }
            $i++;
        }
    }
}

testReadWriteAsyncSingleClient();
--EXPECT--
