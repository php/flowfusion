--TEST--
Translated seed (inferredbugs-csharp: cscs/18)
--FILE--
<?php
$a = [1, 2, 3];
$b = &$a;
array_splice($b, 1, 1);
$a[] = 4;

$s = "123";
$n = (int)$s;
$f = (float)"1.5e2";
$bool = filter_var("true", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

$map = [];
$map["x"] = ["y" => 1];
$map["x"]["z"] = 2;

$items = [["a", "1"], ["a", "2"], ["b", "3"], ["b", "4"]];
$out = [];
$prev = null;
$count = 0;
foreach ($items as $row) {
    if ($row[0] !== $prev) {
        $prev = $row[0];
        $count = 0;
    }
    $out[] = $count++;
}

$props = [];
$obj = new stdClass();
$obj->p = 7;
$props[] = get_object_vars($obj);
$obj->q = 8;

$nested = [["k" => "v"], ["k" => "w"]];
$copy = unserialize(serialize($nested));
$copy[0]["k"] = "x";

echo json_encode([$a, $n, $f, $bool, $map, $out, $props, $copy], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
