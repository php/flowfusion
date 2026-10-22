--TEST--
Translated seed (inferredbugs-csharp: Hyperion/7)
--FILE--
<?php
$items = [null, 0, 1, -1, 2147483647, "0", "1", "", true, false, 3.14, [1], new stdClass()];
$len = count($items);
$out = [];
for ($i = 0; $i < $len; $i++) {
    $out[] = $items[$i];
}
for ($i = 0; $i < $len; $i++) {
    $v = $out[$i];
    if (is_array($v)) {
        $out[$i] = $v[0] ?? null;
    } elseif (is_object($v)) {
        $out[$i] = get_object_vars($v);
    } elseif (is_string($v) && $v !== "") {
        $out[$i] = $v . "";
    } else {
        $out[$i] = $v;
    }
}
foreach ($out as $k => $v) {
    $out[$k] = $v;
}
?>
--EXPECT--
