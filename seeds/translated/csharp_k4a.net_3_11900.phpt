--TEST--
Translated seed (inferredbugs-csharp: k4a.net/3)
--FILE--
<?php
$bodyCount = 3;
$bodyIds = [101, 102, 103];
$skel = [
    ['joint' => 'head', 'x' => 1, 'y' => 2],
    ['joint' => 'hand', 'x' => 3, 'y' => 4],
    ['joint' => 'foot', 'x' => 5, 'y' => 6],
];

foreach ([-1, $bodyCount, 2147483647] as $i) {
    if ($i < 0 || $i >= $bodyCount) {
        continue;
    }
    $id = $bodyIds[(int)$i];
    $s = $skel[(int)$i];
}

for ($i = 0; $i < $bodyCount; $i++) {
    $id = $bodyIds[$i];
    $s = $skel[$i];
    $copy = $s;
    $copy['x'] += 10;
}

echo "ok\n";
--EXPECT--
