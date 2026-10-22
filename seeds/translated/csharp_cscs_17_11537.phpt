--TEST--
Translated seed (inferredbugs-csharp: cscs/17)
--FILE--
<?php
$map = [];
$map["a"] = 1;
$map["A"] = 2;
$map[""] = 3;
$map["0"] = 4;
$map[0] = 5;
$map["01"] = 6;
$map["1e2"] = 7;
$map[" 1 "] = 8;
$map["1.0"] = 9;
$map["-0"] = 10;
$map["true"] = true;
$map["false"] = false;
$map["null"] = null;

$keys = array_keys($map);
sort($keys, SORT_REGULAR);

$out = [];
foreach ($keys as $k) {
    $v = $map[$k];
    $out[] = is_bool($v) ? ($v ? "1" : "") : (is_null($v) ? "null" : (string)$v);
}

$lines = ["x\ty\tz", "1\t2\t3", "4\t5\t6", "7\t8\t9"];
$tokens = [];
for ($i = 1; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}

$counts = [];
$prev = null;
for ($i = 0; $i < count($tokens); $i++) {
    $cur = $tokens[$i][1];
    if ($cur !== $prev) {
        $prev = $cur;
        $counts[] = 0;
    } else {
        $counts[] = end($counts);
    }
}

echo implode("|", $out) . "\n";
echo implode(",", $counts) . "\n";
echo strlen((string)$map["1e2"]) . "\n";
echo gettype($map["true"]) . "\n";
?>
--EXPECT--
