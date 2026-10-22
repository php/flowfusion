--TEST--
Translated seed (inferredbugs-csharp: dlr/4)
--FILE--
<?php
$none = null;
$invalid = null;
$spans = [
    [$none, $none],
    [$invalid, $invalid],
    [0, 5],
    [5, 5],
    [2, 7],
    [7, 2],
];
foreach ($spans as [$start, $end]) {
    if (($start === null) !== ($end === null)) {
        continue;
    }
    $length = $end - $start;
    $hash = (($start & 127) ^ (($end & 127) << 7) ^ (($start >> 7) << 14) ^ (($end >> 7) << 23));
    (string)$length;
    (string)$hash;
}
echo "ok\n";
?>
--EXPECT--
