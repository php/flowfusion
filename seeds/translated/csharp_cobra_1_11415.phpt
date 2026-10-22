--TEST--
Translated seed (inferredbugs-csharp: cobra/1)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
$values = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN, 1.0, -0.0, INF, -INF, NAN, "0", "1", "-1", "1e3", " 42 ", "foo", "", null, true, false, [], [1], ["1"], ["a" => 1]];
foreach ($values as $v) {
    $s = serialize($v);
    $u = unserialize($s);
    $a = [$v, $u];
    $b = array_merge($a, $a);
    $c = array_unique($b, SORT_REGULAR);
    fwrite($h, json_encode($c, JSON_PARTIAL_OUTPUT_ON_ERROR) . "\n");
}
rewind($h);
while (!feof($h)) {
    fgets($h);
}
fclose($h);
?>
--EXPECT--
