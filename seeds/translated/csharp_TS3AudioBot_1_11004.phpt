--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/1)
--FILE--
<?php
$tests = [
    ["1", "==", "1.0000004", "ok", "no"],
    ["10", "<", "2", "bad", "ok"],
    ["a", ">", "b", "ok", "no"],
];

foreach ($tests as $t) {
    [$a, $op, $b, $then, $else] = $t;
    $cmp = null;
    switch ($op) {
        case "<": $cmp = fn($x, $y) => $x < $y; break;
        case ">": $cmp = fn($x, $y) => $x > $y; break;
        case "<=": $cmp = fn($x, $y) => $x <= $y; break;
        case ">=": $cmp = fn($x, $y) => $x >= $y; break;
        case "==": $cmp = fn($x, $y) => abs($x - $y) < 1e-6; break;
        case "!=": $cmp = fn($x, $y) => abs($x - $y) > 1e-6; break;
    }
    $r = is_numeric($a) && is_numeric($b) ? $cmp((float)$a, (float)$b) : $cmp(strcmp($a, $b), 0);
    echo $r ? $then : $else, "\n";
}
?>
--EXPECT--
