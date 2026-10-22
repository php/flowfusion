--TEST--
Translated seed (inferredbugs-csharp: RealTimeGraphX/1)
--FILE--
<?php
$min = 1.0;
$max = 1.0;
$value = 1.0;
$result = (($value - $min) * 100) / ($max - $min);
if (is_nan($result) || is_infinite($result)) {
    $result = $min;
}
$abs = $min + ($max - $min) * 0.25;
$range = [];
$count = 5;
for ($i = 0; $i < $count; $i++) {
    $range[] = (float)($min + ($max - $min) * ($i / ($count - 1)));
}
echo (string)$result, "\n", (string)$abs, "\n", implode(",", array_map(fn($x) => (string)$x, $range)), "\n";
?>
--EXPECT--
