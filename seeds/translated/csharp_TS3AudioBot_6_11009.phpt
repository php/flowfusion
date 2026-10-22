--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/6)
--FILE--
<?php
declare(strict_types=1);

$tests = [
    ['0', '==', '0.0000001', 'then', 'else'],
    ['1e309', '>', '1', 'then', 'else'],
    ['foo', '<', 'bar', 'then', 'else'],
    ['12:34', ':', 'x', 'then', 'else'],
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
        default: $cmp = fn($x, $y) => strcmp((string)$x, (string)$y) < 0;
    }
    if (is_numeric($a) && is_numeric($b)) {
        $r = $cmp((float)$a, (float)$b);
    } else {
        $r = $cmp(strcmp((string)$a, (string)$b), 0);
    }
    echo $r ? $then : $else, "\n";
}

$s = "a b  c";
$p = preg_split('/\s+/', $s);
echo implode(' ', array_slice($p, 0, 2)), "\n";

$x = json_encode(['position' => 65, 'length' => 3665], JSON_UNESCAPED_SLASHES);
echo $x, "\n";
?>
--EXPECT--
