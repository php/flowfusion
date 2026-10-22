--TEST--
Translated seed (inferredbugs-csharp: PeanutButter/1)
--FILE--
<?php
$items = [null, false, true, 0, 1, "0", "1", "", " 1 ", "foo", "10", "010", "1e2", "9223372036854775807", "-1"];
$sum = 0;
foreach ($items as $v) {
    $n = filter_var($v, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    if ($n === null) {
        $n = is_numeric($v) ? (int)$v : 0;
    }
    $sum += $n;
}
$map = [];
foreach ($items as $k => $v) {
    $key = (string)$v;
    $map[$key] = ($map[$key] ?? 0) + 1;
}
$buf = str_repeat("A", 3);
$buf[1] = "B";
$buf .= chr(0);
$parts = explode(chr(0), $buf);
sort($parts);
echo $sum, "\n";
echo count($map), "\n";
echo implode(",", $parts), "\n";
echo strlen($buf), "\n";
?>
--EXPECT--
