--TEST--
Translated seed (inferredbugs-csharp: netDxf/1)
--FILE--
<?php
$items = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
$map = [];
for ($i = 0; $i < 2000; $i++) {
    $k = "k" . ($i % 97);
    $map[$k] = $i;
}
for ($i = 0; $i < 5000; $i++) {
    $s = "\\U+" . sprintf("%04X", ($i * 37) & 0xFFFF) . "x" . ($i % 13);
    $out = '';
    $len = strlen($s);
    for ($j = 0; $j < $len; $j++) {
        $c = $s[$j];
        if ($c === '\\' && $j + 6 < $len && ($s[$j + 1] === 'U' || $s[$j + 1] === 'u') && $s[$j + 2] === '+') {
            $hex = substr($s, $j + 3, 4);
            if (ctype_xdigit($hex)) {
                $c = chr(hexdec($hex) & 0xFF);
                $j += 6;
            }
        }
        $out .= $c;
    }
    $items[] = strlen($out) ^ ($map["k" . ($i % 97)] ?? 0);
}
sort($items);
echo count($items), "\n";
?>
--EXPECT--
