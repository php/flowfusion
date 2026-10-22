--TEST--
Translated seed (inferredbugs-csharp: dnlib/3)
--FILE--
<?php
$streamHeaders = [
    ["Name" => "#x", "Offset" => PHP_INT_MAX, "Size" => 1],
    ["Name" => "#-", "Offset" => 0, "Size" => 16],
];

$mdSize = 16;
$type = 0;
foreach ($streamHeaders as $sh) {
    $end = $sh["Offset"] + $sh["Size"];
    if ($end < $sh["Offset"] || $end > $mdSize) {
        $type = -1;
        break;
    }
    if ($sh["Name"] === "#~") {
        $type = 1;
        break;
    }
    if ($sh["Name"] === "#-") {
        $type = 2;
        break;
    }
}

if ($type === 1) {
    $meta = "Compressed";
} elseif ($type === 2) {
    $meta = "ENC";
} else {
    $meta = "Unknown";
}

echo $meta, PHP_EOL;
?>
--EXPECT--
