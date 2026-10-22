--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/4)
--FILE--
<?php
declare(strict_types=1);

$tests = [
    ['a', '<', 'b', 'ok', 'no'],
    ['1.0000001', '==', '1.0000002', 'eq', 'neq'],
    ['10', '>', '2', 'gt', 'le'],
    ['foo', '!=', 'bar', 's', 't'],
    ['1e309', '<', '2', 'inf', 'finite'],
    ['1', '??', '2', 'x', 'y'],
];

foreach ($tests as $t) {
    [$a, $op, $b, $then, $else] = $t;
    $cmp = null;
    switch ($op) {
        case '<': $cmp = fn($x, $y) => $x < $y; break;
        case '>': $cmp = fn($x, $y) => $x > $y; break;
        case '<=': $cmp = fn($x, $y) => $x <= $y; break;
        case '>=': $cmp = fn($x, $y) => $x >= $y; break;
        case '==': $cmp = fn($x, $y) => abs($x - $y) < 1e-6; break;
        case '!=': $cmp = fn($x, $y) => abs($x - $y) > 1e-6; break;
        default: $cmp = null;
    }
    if ($cmp === null) {
        continue;
    }
    $n0 = is_numeric($a);
    $n1 = is_numeric($b);
    $res = $n0 && $n1 ? $cmp((float)$a, (float)$b) : $cmp(strcmp($a, $b), 0);
    echo $res ? $then : $else, PHP_EOL;
}
--EXPECT--
