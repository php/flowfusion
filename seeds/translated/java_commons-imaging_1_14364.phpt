--TEST--
Translated seed (inferredbugs-java: commons-imaging/1)
--FILE--
<?php

$bytes = [];
for ($i = 0; $i < 100; $i++) {
    $bytes[] = $i & 0xff;
}

foreach ($bytes as $counter => $data) {
    echo "$counter: $data (" . implode(',', array_map(function($byte) { return $byte & 0xff; }, array($data))) . ") [0x" . dechex($data) . ", " . decbin($data) . "]\n";
}

$haystack = array_slice($bytes, 0, 50);
$needle = array_slice($bytes, 0, 10);
$match = true;

if (count($needle) > count($haystack)) $match = false;

for ($i = 0; $i < count($needle); $i++) {
    if ($needle[$i] !== $haystack[$i]) {
        $match = false;
        break;
    }
}

if ($match) {
    echo "Needle matches haystack.\n";
} else {
    echo "Needle does not match haystack.\n";
}

$readBytes = array_slice($bytes, 10, 10);
$expected = array_slice($bytes, 10, 10);

foreach ($expected as $i => $value) {
    if ($readBytes[$i] !== $expected[$i]) {
        echo "Mismatch at index $i: read {$readBytes[$i]}, expected {$expected[$i]}.\n";
        exit(1);
    }
}

echo "All bytes matched.\n";
--EXPECT--
