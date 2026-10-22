--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/8)
--FILE--
<?php
$values = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN, 0.0, -0.0, INF, -INF, NAN, "0", "1", "-1", "1e3", "foo", [], [1], null, true, false];
$sum = 0;
foreach ($values as $a) {
    foreach ($values as $b) {
        $x = $a + $b;
        $y = $a * $b;
        $z = $a / ($b ?: 1);
        $w = $a <=> $b;
        $sum += is_int($w) ? $w : 0;
        if (is_array($x) || is_array($y) || is_array($z)) {
            $sum++;
        }
    }
}

$arr = [];
$arr["0"] = "a";
$arr[0] = "b";
$arr[false] = "c";
$arr[null] = "d";
$arr["1e2"] = "e";
$arr[100] = "f";

$copy = $arr;
$copy[] = $copy;
$copy["self"] = &$copy;
$copy["mix"] = $arr + $copy;

for ($i = 0; $i < 5; $i++) {
    foreach ($copy as $k => $v) {
        if (is_array($v)) {
            $sum += count($v);
        } elseif (is_string($v)) {
            $sum += strlen($v);
        }
    }
}

$fp = fopen("php://memory", "r+");
fwrite($fp, serialize($copy));
rewind($fp);
$data = unserialize(stream_get_contents($fp));
fclose($fp);

echo $sum, "\n";
echo count($arr), "\n";
echo is_array($data) ? count($data) : 0, "\n";
?>
--EXPECT--
