--TEST--
Translated seed (inferredbugs-csharp: P/19)
--FILE--
<?php
$xs = [null, 0, "0", false, [], [1], "", "1", 1];
$h = [];
foreach ($xs as $x) {
    $h[] = [$x, in_array($x, $xs, true), array_search($x, $xs, true)];
}
$a = ["k" => 1];
$b = $a;
$b["k"] = 2;
$c = [&$a, &$b];
foreach ($c as &$v) {
    $v["x"] = $v["k"] ?? 0;
}
unset($v);
$u = [];
$u[] = $u;
$s = serialize($u);
$u2 = unserialize($s);
$r = [];
foreach ($u2 as $v) {
    $r[] = is_array($v) && array_key_exists(0, $v);
}
echo count($h) . "\n";
echo count($r) . "\n";
echo ($a["x"] ?? 0) . " " . ($b["x"] ?? 0) . "\n";
echo strlen($s) . "\n";
?>
--EXPECT--
