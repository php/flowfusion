--TEST--
Translated seed (inferredbugs-csharp: cscs/80)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$tokens = [];
foreach ($lines as $line) {
    $tokens[] = explode("\t", $line);
}
$map = [];
foreach ($tokens as $i => $row) {
    $k = $row[0];
    if (!isset($map[$k])) $map[$k] = [];
    $map[$k][] = $i;
}
$all = [];
foreach ($tokens as $i => $row) {
    $all[] = [$row[0], $i];
}
$col = [];
foreach ($all as $row) {
    $col[] = $row[0];
}
$idx = [];
$prev = null;
$n = 0;
foreach ($col as $v) {
    if ($v !== $prev) {
        $prev = $v;
        $n = 0;
    }
    $idx[] = $n++;
}
$s = serialize([$tokens, $map, $col, $idx]);
unserialize($s);
echo "ok\n";
?>
--EXPECT--
