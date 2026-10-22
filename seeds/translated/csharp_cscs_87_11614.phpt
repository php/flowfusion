--TEST--
Translated seed (inferredbugs-csharp: cscs/87)
--FILE--
<?php
$keys = [str_repeat("a", 1000), str_repeat("a", 999) . "b", str_repeat("a", 500) . "." . str_repeat("b", 500)];
$a = [];
foreach ($keys as $k) {
    $a[$k] = ["x" => 1, "y" => ["z" => 2]];
}
$ns = [];
$ns["mod"] = $a;
$stack = [];
for ($i = 0; $i < 5; $i++) {
    $stack[] = ["vars" => [], "ns" => ($i === 2)];
}
$top = &$stack[count($stack) - 1]["vars"];
$top[$keys[0]] = 123;
$top[$keys[1]] = 456;
$ref = &$stack[2]["vars"];
$ref[$keys[2]] = $ns["mod"][$keys[0]];
$path = "dir/sub/file.txt";
$base = basename($path);
$localScope = [];
$localScope[$base][$keys[0]] = 789;
$localScope[$base][$keys[2]] = ["p" => ["q" => 3]];
$out = [];
foreach ($stack as $level) {
    foreach ($level["vars"] as $n => $v) {
        if (is_array($v)) {
            foreach ($v as $pn => $pv) {
                $out[] = $n . "." . $pn . "=" . (is_array($pv) ? json_encode($pv) : $pv);
            }
        } else {
            $out[] = $n . "=" . $v;
        }
    }
}
foreach ($localScope[$base] as $n => $v) {
    $out[] = $base . ":" . $n . "=" . (is_array($v) ? json_encode($v) : $v);
}
echo implode("\n", $out), "\n";
?>
--EXPECT--
