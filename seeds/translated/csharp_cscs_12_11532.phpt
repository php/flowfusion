--TEST--
Translated seed (inferredbugs-csharp: cscs/12)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$tokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($tokens); $i++) {
    $k = $tokens[$i][0];
    $map[$k] = $i;
    if ($k !== "A" && strcasecmp($k, "a") !== 0) {
        $map["A"] = $i;
    }
}
$counts = [];
$prev = null;
for ($i = 0; $i < count($tokens); $i++) {
    $v = $tokens[$i][0];
    if ($v !== $prev) {
        $prev = $v;
        $c = 0;
    }
    $counts[] = $c;
    $c++;
}
$arr = [];
$arr[2] = ["x" => 1, "y" => 2];
$arr[] = ["z" => 3];
$arr[0]["x"] += 5;
$arr[1] = $arr[2];
$size = count($arr);
$type = gettype($arr[0]["x"]);
$joined = implode(",", array_keys($map)) . ":" . implode(",", array_map('strval', $counts)) . ":" . $size . ":" . $type;
echo $joined, PHP_EOL;
?>
--EXPECT--
