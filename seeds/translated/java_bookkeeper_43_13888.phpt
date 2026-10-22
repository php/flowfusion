--TEST--
Translated seed (inferredbugs-java: bookkeeper/43)
--FILE--
<?php

$ledgerId = 1;
$lastAddPushed = 0;
$length = 0;

function addEntry(&$lastAddPushed, &$length, $data) {
    if ($data < 0) {
        throw new Exception("Negative data not allowed");
    }
    $lastAddPushed++;
    $length += $data;
    return $lastAddPushed;
}

$data = 5;
try {
    for ($i = 0; $i < 10; $i++) {
        $entryId = addEntry($lastAddPushed, $length, $data);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Last Add Pushed: $lastAddPushed\n";
echo "Length: $length\n";

$data = -5;
try {
    $entryId = addEntry($lastAddPushed, $length, $data);
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
