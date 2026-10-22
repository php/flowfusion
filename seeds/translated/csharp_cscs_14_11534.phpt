--TEST--
Translated seed (inferredbugs-csharp: cscs/14)
--FILE--
<?php
$lines = ["a\t1\t2", "b\t3\t4", "a\t5\t6"];
$tokens = [];
foreach ($lines as $i => $line) {
    $tokens[$i] = explode("\t", $line);
}
$counts = [];
$prev = null;
foreach ($tokens as $row) {
    $v = $row[0];
    if ($v !== $prev) {
        $prev = $v;
        $counts[$v] = 0;
    }
    $counts[$v]++;
}
$map = [];
foreach ($tokens as $i => $row) {
    $map[$row[0]] = $i;
    if ($row[0] !== 'x') {
        $map['x'] = $i;
    }
}
$arr = [];
$arr[2] = [];
$arr[2][1] = "7";
$arr[2][1] += 3;
$type = gettype($arr[2][1]);
$size = is_array($arr) ? count($arr) : strlen((string)$arr);
echo json_encode([$counts, $map, $arr, $type, $size], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
