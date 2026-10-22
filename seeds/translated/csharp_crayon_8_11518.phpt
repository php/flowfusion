--TEST--
Translated seed (inferredbugs-csharp: crayon/8)
--FILE--
<?php
$tokens = ['0', '.', '5', ';', 'a1', '_x', 'Z9', '123', 'foo_bar', '9x', 'A', 'b2'];
foreach ($tokens as $t) {
    $ok = true;
    $len = strlen($t);
    for ($i = $len - 1; $i >= 0; --$i) {
        $c = $t[$i];
        if (!(($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || ($c >= '0' && $c <= '9' && $i > 0) || $c === '_')) {
            $ok = false;
            break;
        }
    }
    if ($ok && $t !== '' && $t[0] >= '0' && $t[0] <= '9') {
        $num = $t;
        if ($num === '0' || $num === '1' || $num === '2') {
            continue;
        }
        for ($i = strlen($num) - 1; $i >= 0; --$i) {
            $c = $num[$i];
            if ($c < '0' || $c > '9') {
                $ok = false;
                break;
            }
        }
    }
}
$cases = [
    ['x' => 1, 'y' => 2],
    ['x' => '1', 'y' => '2'],
    ['x' => 0, 'y' => 0],
];
foreach ($cases as $case) {
    $v = $case['x'] + $case['y'];
    $w = ($v === 3) ? 1 : 0;
    $z = $w ? ($v << 1) : ($v >> 1);
    $r = ($z & 1) | ($z ^ 2);
}
echo "ok\n";
?>
--EXPECT--
