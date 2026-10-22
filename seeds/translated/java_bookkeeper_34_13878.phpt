--TEST--
Translated seed (inferredbugs-java: bookkeeper/34)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = PHP_INT_MAX;
$rng = new Random();
$entries = [];
$ledgerId = 0;

class LedgerHandle {
    private $entries = [];
    private $id;
    private $lastConfirmed = -1;

    public function __construct($id) {
        $this->id = $id;
    }

    public function addEntry($entry) {
        $this->entries[] = $entry;
        $this->lastConfirmed++;
    }

    public function getLastAddConfirmed() {
        return $this->lastConfirmed;
    }

    public function getId() {
        return $this->id;
    }

    public function readEntries($start, $end) {
        return array_slice($this->entries, $start, $end - $start + 1);
    }

    public function close() {
    }
}

function createLedger($digestType, $password) {
    global $ledgerId;
    $ledgerId++;
    return new LedgerHandle($ledgerId);
}

function randomInt() {
    return rand(0, PHP_INT_MAX);
}

$lh = createLedger('SHA1', $ledgerPassword);

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = randomInt();
    $entries[] = $entry;
    $lh->addEntry($entry);
}

$lh->close();

$lh = createLedger('SHA1', $ledgerPassword);

if ($lh->getLastAddConfirmed() != ($numEntriesToWrite - 1)) {
    die("Error: Incorrect number of entries written");
}

$readEntries = $lh->readEntries(0, $numEntriesToWrite - 1);
foreach ($readEntries as $i => $entry) {
    if ($entry !== $entries[$i]) {
        die("Error: Entry $i does not match original");
    }
}

$lh->close();
--EXPECT--
