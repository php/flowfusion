--TEST--
Translated seed (inferredbugs-csharp: cscs/102)
--FILE--
<?php
$nan = acos(2);
$inf = 1 / 0;
$negInf = -1 / 0;

var_dump(is_nan($nan));
var_dump(is_finite($inf));
var_dump(is_infinite($negInf));

$a = [];
$a["x"] = 1;
$a["x"] += 2;
$a["y"] = ["z" => 3];
$a["y"]["z"]++;
$a["s"] = "1";
$a["s"] .= "2";
var_dump($a);

$copy = $a;
$copy["y"]["z"] = 99;
var_dump($a["y"]["z"], $copy["y"]["z"]);

$values = ["10", 10, 10.5, true, null, "foo", NAN, INF];
foreach ($values as $v) {
    var_dump(gettype($v), is_numeric($v), is_bool($v), is_null($v));
}

$obj = new stdClass();
$obj->p = ["k" => 1];
$obj->p["k"] = 2;
var_dump($obj);

$arr = [["a" => 1], ["a" => 1], ["a" => 2], ["a" => 2], ["a" => 2]];
$counts = [];
$prev = null;
$c = 0;
foreach ($arr as $row) {
    $cur = $row["a"];
    if ($cur !== $prev) {
        $prev = $cur;
        $c = 0;
    }
    $counts[] = $c++;
}
var_dump($counts);

$map = [];
foreach (["a\t1", "b\t2", "a\t3"] as $line) {
    [$k, $v] = explode("\t", $line, 2);
    $map[$k][] = $v;
}
var_dump($map);

echo "done\n";
?>
--EXPECT--
