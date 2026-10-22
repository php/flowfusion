--TEST--
Translated seed (inferredbugs-java: bookkeeper/36)
--FILE--
<?php

$maxInt = 2147483647;
$numEntriesToWrite = 200;
$entries = [];
$syncCounter = 0;

class LedgerHandle {
    private $id;
    private $entries = [];

    public function __construct($id) {
        $this->id = $id;
    }

    public function addEntry($entry) {
        $this->entries[] = $entry;
    }

    public function getLastAddConfirmed() {
        return count($this->entries) - 1;
    }

    public function getId() {
        return $this->id;
    }

    public function readEntries($start, $end) {
        return array_slice($this->entries, $start, $end - $start + 1);
    }
}

function createLedger($id) {
    return new LedgerHandle($id);
}

function randomInt($max) {
    return rand(0, $max);
}

$lh = createLedger(1);

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = pack("N", randomInt($maxInt));
    $entries[] = $entry;
    $lh->addEntry($entry);
}

$lastConfirmed = $lh->getLastAddConfirmed();

if ($lastConfirmed !== ($numEntriesToWrite - 1)) {
    echo "Error: Expected last confirmed add to be " . ($numEntriesToWrite - 1) . ", got $lastConfirmed\n";
    exit(1);
}

$readEntries = $lh->readEntries(0, $numEntriesToWrite - 1);
foreach ($readEntries as $i => $entry) {
    $originalEntry = unpack("N", $entry)[1];
    if ($originalEntry !== unpack("N", $entries[$i])[1]) {
        echo "Error: Entry mismatch at index $i\n";
        exit(1);
    }
}

echo "Test completed successfully.\n";

?>
--EXPECT--
