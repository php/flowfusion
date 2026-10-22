--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/32)
--FILE--
<?php
$cases = [
    [null, "tan"],
    ["abc", "tan"],
    ["abcdef", 2, 3],
    ["abcdef", 2],
    ["hello", "ell"],
    ["hello", "he"],
    ["hello", "lo"],
    ["", ""],
    ["x", ["a", "b", "c"]],
    [123, null],
];

foreach ($cases as $c) {
    $a = $c[0] ?? null;
    $b = $c[1] ?? null;

    $r = $a ?? $b;
    $s = is_string($a) ? $a : (string)$a;
    $t = is_string($b) ? $b : (string)$b;

    $u = substr((string)$c[0], (int)($c[1] ?? 0), isset($c[2]) ? (int)$c[2] : strlen((string)$c[0]));
    $v = str_contains($s, $t);
    $w = str_starts_with($s, $t);
    $x = str_ends_with($s, $t);
    $y = strpos($s, $t);
    $z = strlen($s);
    $q = (string)floor((float)($c[0] ?? 0));
    $n = bin2hex(random_bytes(8));

    $m = implode('', array_map(fn($e) => (string)$e, is_array($b) ? $b : [$b]));
    $p = $r . $u . ($v ? '1' : '0') . ($w ? '1' : '0') . ($x ? '1' : '0') . ($y === false ? '-1' : (string)$y) . $z . $q . $n . $m;
    if ($p === '') echo '';
}
?>
--EXPECT--
