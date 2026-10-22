--TEST--
Translated seed (inferredbugs-csharp: cscs/40)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "b\t5"];
$allTokens = [];
for ($i = 0, $n = count($lines); $i < $n; $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
$currentValue = null;
$currentCount = 0;
for ($i = 0, $n = count($allTokens); $i < $n; $i++) {
    $newValue = $allTokens[$i][0];
    if ($currentValue !== $newValue) {
        $currentValue = $newValue;
        $currentCount = 0;
    }
    $map[] = $currentCount;
    $currentCount++;
}
$arr = [];
$arr[2] = [];
$arr[2][3] = "x";
$arr["k"] = "v";
$copy = unserialize(serialize($arr));
$copy[2][3] = "y";
$s = "123";
$int = (int)$s;
$float = (float)$s;
$bool = filter_var("true", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
$decimal = (string)number_format((float)"1.25", 2, ".", "");
$props = array_keys(get_object_vars((object)["p" => 1, "q" => 2]));
$nested = ["a" => ["b" => ["c" => 7]]];
$v = $nested["a"]["b"]["c"];
echo "";
?>
--EXPECT--
