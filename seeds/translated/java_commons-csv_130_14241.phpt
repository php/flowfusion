--TEST--
Translated seed (inferredbugs-java: commons-csv/130)
--FILE--
<?php

$code = "one,two,three\n"
    . "'',''\n"
    . "/',/'\n"
    . "'/'','/''\n"
    . "'''',''''\n"
    . "/,,/,\n"
    . "//,//\n"
    . "'//','//'\n"
    . "   8   ,   \"quoted \"\" /\" // string\"   \n"
    . "9,   /\n   \n";

$expected = [
    ["one", "two", "three"],
    ["", ""],
    ["'", "'"],
    ["'", "'"],
    ["'", "'"],
    [",", ","],
    ["/", "/"],
    ["/", "/"],
    ["   8   ", "   \"quoted \"\" /\" / string\"   "],
    ["9", "   \n   "],
];

$lines = explode("\n", $code);
$results = [];

foreach ($lines as $line) {
    if (trim($line) === '') continue; // Skip empty lines
    $results[] = preg_split('/,/', $line);
}

foreach ($expected as $index => $exp) {
    for ($i = 0; $i < count($exp); $i++) {
        if (!isset($results[$index][$i])) {
            $results[$index][$i] = ''; // Fill missing fields for comparison
        }
    }
}

$resultsCount = count($results);
$expectedCount = count($expected);

if ($resultsCount !== $expectedCount) {
    exit(1);
}

for ($i = 0; $i < $expectedCount; $i++) {
    if ($results[$i] !== $expected[$i]) {
        exit(1);
    }
}

exit(0);
--EXPECT--
