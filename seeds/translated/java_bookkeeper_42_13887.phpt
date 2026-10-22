--TEST--
Translated seed (inferredbugs-java: bookkeeper/42)
--FILE--
<?php

class SyncObj {
    public $counter = 0;
    public $value = false;
}

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];
$sync = new SyncObj();

function addEntry($entry, $sync) {
    $sync->counter++;
}

function readEntries($entries, $sync) {
    $sync->value = true;
}

$numEntries = 100;

for ($i = 0; $i < $numEntries; $i++) {
    $data = strval($i);
    array_push($entries, $data);
}

$sync = new SyncObj();
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    addEntry($entry, $sync);
    array_push($entries, pack('N', $entry));
}

$failedBookie = true;

if ($failedBookie) {
    $syncCounter = $sync->counter;
}

$syncCounter = count($entries) - 1;

for ($i = 0; $i < count($entries); $i++) {
    $retrEntry = unpack('N', $entries[$i])[1];
    if ($retrEntry !== unpack('N', $entries[$i])[1]) {
        exit(1);
    }
}

$sync->value = true;

while (!$sync->value) {
    usleep(100000);
}

$syncCounter = count($entries);
if ($syncCounter !== $numEntriesToWrite) {
    exit(1);
}

$sync->value = false;
--EXPECT--
