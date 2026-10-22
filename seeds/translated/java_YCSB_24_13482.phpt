--TEST--
Translated seed (inferredbugs-java: YCSB/24)
--FILE--
<?php

$table = 'usertable';
$fieldcount = 10;
$recordcount = 100;
$zeropadding = 1;

function buildKeyName($keynum, $zeropadding) {
    $value = (string)$keynum;
    $fill = $zeropadding - strlen($value);
    $prekey = "user";
    for ($i = 0; $i < $fill; $i++) {
        $prekey .= '0';
    }
    return $prekey . $value;
}

function doInsert($keynum, $zeropadding) {
    $dbkey = buildKeyName($keynum, $zeropadding);
    // Simulate insertion logic here (e.g., inserting into a database)
}

for ($i = 0; $i < $recordcount; $i++) {
    doInsert($i, $zeropadding);
}
--EXPECT--
