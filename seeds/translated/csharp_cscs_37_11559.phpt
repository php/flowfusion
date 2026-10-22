--TEST--
Translated seed (inferredbugs-csharp: cscs/37)
--FILE--
<?php
function main() {
    $a = [];
    $a['x'] = ['y' => 1];
    $b = $a;
    $b['x']['y']++;
    $c = $a['x']['y'] ?? null;
    $d = $b['x']['y'] ?? null;
    $s = "12";
    $n = (int)$s;
    $f = (float)"3.14";
    $t = (bool)"0";
    $e = [];
    $e[] = $c;
    $e[] = $d;
    $e[] = $n;
    $e[] = $f;
    $e[] = $t;
    $e[] = isset($a['x']['z']) ? 1 : 0;
    $e[] = array_key_exists('x', $a) ? 1 : 0;
    $e[] = count($a);
    $e[] = count($b);
    $e[] = json_decode(json_encode($b), true)['x']['y'] ?? null;
    $e[] = implode(',', array_map(fn($v) => (string)$v, $e));
    echo "";
}
main();
?>
--EXPECT--
