--TEST--
Translated seed (inferredbugs-csharp: cscs/83)
--FILE--
<?php
$vals = [];
$vals[""] = 1;
$vals["0"] = 2;
$vals["00"] = 3;
$vals["a.b"] = 4;
$vals["a"] = ["b" => 5, "c" => ["d" => 6]];
$vals["x.y.z"] = 7;

$keys = array_keys($vals);
sort($keys, SORT_STRING);

$out = "";
foreach ($keys as $k) {
    $v = $vals[$k];
    if (is_array($v)) {
        $out .= $k . ":" . count($v) . "\n";
        foreach ($v as $pk => $pv) {
            $out .= $k . "." . $pk . ":" . (is_array($pv) ? count($pv) : $pv) . "\n";
        }
    } else {
        $out .= $k . ":" . $v . "\n";
    }
}

$stack = [];
$stack[] = ["id" => 1, "vars" => ["p" => 10, "q" => 20]];
$stack[] = ["id" => 2, "vars" => ["p" => 30]];
$stack[] = ["id" => 3, "vars" => ["r" => 40]];
array_pop($stack);
$stack[] = ["id" => 4, "vars" => ["s" => 50]];

foreach ($stack as $frame) {
    foreach ($frame["vars"] as $n => $v) {
        $out .= $frame["id"] . ":" . $n . "=" . $v . "\n";
    }
}

echo trim($out), "\n";
?>
--EXPECT--
