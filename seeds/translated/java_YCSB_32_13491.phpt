--TEST--
Translated seed (inferredbugs-java: YCSB/32)
--FILE--
<?php

$y_id = "key";
$length = 100;
$values = [];
for ($i = 0; $i < $length; $i++) {
    $values["field" . $i] = str_repeat("x", PHP_INT_MAX);
}

$insertedLength = 0;

foreach ($values as $key => $value) {
    $insertedLength += strlen($value);
}

if ($insertedLength > $length) {
    echo "Potential overflow triggered\n";
}

array_splice($values, 0, count($values), []);
?>
--EXPECT--
