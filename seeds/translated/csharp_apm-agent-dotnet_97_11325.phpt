--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/97)
--FILE--
<?php
$xs = [PHP_INT_MAX, PHP_INT_MAX - 1, -1, 0, 1, 2];
foreach ($xs as $x) {
    $a = $x + 1;
    $b = $x - 1;
    $c = intdiv($x, 2);
    $d = $x % 2;
    $e = (string)$x;
    $f = json_decode(json_encode([$x]), true)[0];
    $g = filter_var($e, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    $h = match (true) {
        $x > 0 => "pos",
        $x < 0 => "neg",
        default => "zero",
    };
    $i = [$a, $b, $c, $d, $e, $f, $g, $h];
}
$y = [];
for ($i = 0; $i < 3; $i++) {
    $y[] = &$i;
}
unset($i);
foreach ($y as $v) {
    $z = $v;
}
echo "ok\n";
?>
--EXPECT--
