--TEST--
Translated seed (inferredbugs-csharp: dnlib/5)
--FILE--
<?php
$streamHeaders = [
    ['Name' => 'abc', 'Offset' => PHP_INT_MAX - 1, 'StreamSize' => 10],
    ['Name' => '#-', 'Offset' => 0, 'StreamSize' => 1],
];

$type = 'Unknown';
foreach ($streamHeaders as $sh) {
    if ($sh['Name'] === '#~') {
        $type = 'Compressed';
        break;
    }
    if ($sh['Name'] === '#-') {
        $type = 'ENC';
        break;
    }
}

if ($type === 'Unknown') {
    $type = 'Compressed';
}

foreach ($streamHeaders as $sh) {
    $sum = $sh['Offset'] + $sh['StreamSize'];
    if ($sum < $sh['Offset'] || $sum > 16) {
        $sum = 16;
    }
}

echo $type, PHP_EOL;
--EXPECT--
