--TEST--
Translated seed (inferredbugs-csharp: c-raft/5)
--FILE--
<?php
$w = 0;
for ($i = 0; $i < 30000; $i++) {
    $w++;
    $t = $w % 24000;
    if ($t === 0) $t = 0;
    if ($w % 10 === 0) { $a = $w + 1; }
    if ($w % 20 === 0) { $b = $w + 2; }
    if ($w % 100 === 0) { $c = $w + 3; }
    if ($w % 200 === 0) { $d = $w + 4; }
    if (($w & 1) === 0) { $e = $w + 5; }
}

$grid = [];
for ($x = -2; $x <= 2; $x++) {
    for ($z = -2; $z <= 2; $z++) {
        $grid[$x >> 4][$z >> 4] = ($x === 0 && $z === 0) ? 1 : 0;
    }
}

for ($x = -1.9; $x <= 1.9; $x += 0.7) {
    for ($z = -1.9; $z <= 1.9; $z += 0.7) {
        $cx = (int)floor($x) >> 4;
        $cz = (int)floor($z) >> 4;
        $v = $grid[$cx][$cz] ?? null;
        if ($v !== null) {
            $grid[$cx][$cz] = $v ^ 1;
        }
    }
}

$bb = [-0.25, -0.25, -0.25, 1.25, 1.25, 1.25];
for ($x = (int)floor($bb[0]); $x < (int)floor($bb[3] + 1); $x++) {
    for ($z = (int)floor($bb[2]); $z < (int)floor($bb[5] + 1); $z++) {
        for ($y = (int)floor($bb[1]) - 1; $y < (int)floor($bb[4] + 1); $y++) {
            $k = ($x << 16) ^ ($y << 8) ^ $z;
            $m[$k] = ($m[$k] ?? 0) + 1;
        }
    }
}

$ray = [0.1, 0.1, 0.1];
$end = [3.9, 3.9, 3.9];
$step = [($end[0] - $ray[0]) / 7, ($end[1] - $ray[1]) / 7, ($end[2] - $ray[2]) / 7];
for ($i = 0; $i < 8; $i++) {
    $p = [floor($ray[0]), floor($ray[1]), floor($ray[2])];
    $ray[0] += $step[0];
    $ray[1] += $step[1];
    $ray[2] += $step[2];
    $q = [floor($ray[0]), floor($ray[1]), floor($ray[2])];
    if ($p !== $q) {
        $adj = [$p[0], $q[1], $q[2]];
        $adj2 = [$q[0], $p[1], $q[2]];
        $adj3 = [$q[0], $q[1], $p[2]];
        $s = implode(',', $adj) . implode(',', $adj2) . implode(',', $adj3);
    }
}

echo "";
?>
--EXPECT--
