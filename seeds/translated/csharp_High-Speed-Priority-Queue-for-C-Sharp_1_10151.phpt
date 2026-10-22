--TEST--
Translated seed (inferredbugs-csharp: High-Speed-Priority-Queue-for-C-Sharp/1)
--FILE--
<?php
$q = [];
$c = 0;
foreach ([[1, 2], [1, 1], [2, 1], [3, 3], [2, 0], [4, 2], [5, 2], [6, 1], [7, 0], [8, 4], [9, 3], [10, 1]] as [$item, $priority]) {
    $q[] = [$priority, $c++, $item];
    usort($q, fn($a, $b) => $a[0] <=> $b[0] ?: $a[1] <=> $b[1]);
}
foreach ([2, 5, 1, 42, 7, 8, 9] as $item) {
    foreach ($q as $i => $n) {
        if ($n[2] === $item) {
            unset($q[$i]);
            break;
        }
    }
    $q = array_values($q);
}
foreach ([[11, 0], [12, 5], [13, 1], [14, 0], [15, 2]] as [$item, $priority]) {
    $q[] = [$priority, $c++, $item];
    usort($q, fn($a, $b) => $a[0] <=> $b[0] ?: $a[1] <=> $b[1]);
}
while ($q) {
    array_shift($q);
}
echo "";
?>
--EXPECT--
