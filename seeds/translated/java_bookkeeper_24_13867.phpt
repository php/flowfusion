--TEST--
Translated seed (inferredbugs-java: bookkeeper/24)
--FILE--
<?php

$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$ledgerId = 1;

class SyncObj {
    public $counter = 0;
    public $value = false;
}

function addEntry($entry, $sync) {
    global $entries;
    $entries[] = $entry;
    $sync->counter++;
}

function readEntries($start, $end, $sync) {
    global $entries;
    $result = array_slice($entries, $start, $end - $start + 1);
    if (count($result) > 0) {
        $sync->value = true;
    }
    return $result;
}

$sync = new SyncObj();
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    addEntry($entry, $sync);
}

while ($sync->counter < $numEntriesToWrite) { }

$readEntries = readEntries(0, $numEntriesToWrite - 1, $sync);

$i = 0;
foreach ($readEntries as $entry) {
    if ($entry !== $entries[$i]) {
        throw new Exception("Entry mismatch at index $i");
    }
    $i++;
}

if ($i !== $numEntriesToWrite) {
    throw new Exception("Number of read entries mismatch");
}
--EXPECT--
