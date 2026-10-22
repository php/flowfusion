--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/25)
--FILE--
<?php
$cases = [
    [],
    [1],
    [1, 2, 3],
];

foreach ($cases as $a) {
    $n = count($a);
    if ($n === 0) {
        continue;
    }
    for ($i = 0; $i < $n; $i++) {
        $v = $a[$i];
        $s = (string)$v;
        if ($s === '') {
            $s = '0';
        }
        $out[$i] = $s;
    }
    unset($out);
}

$objs = [
    (object)[],
    (object)['id' => 1],
    (object)['id' => 1, 'name' => 'x'],
];

foreach ($objs as $o) {
    $p = get_object_vars($o);
    $n = count($p);
    if ($n === 0) {
        continue;
    }
    foreach ($p as $k => $v) {
        $tmp[$k] = $v;
    }
    unset($tmp);
}
?>
--EXPECT--
