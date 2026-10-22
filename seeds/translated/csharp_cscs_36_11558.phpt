--TEST--
Translated seed (inferredbugs-csharp: cscs/36)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$allTokens = [];
foreach ($lines as $line) {
    $allTokens[] = explode("\t", $line);
}

$map = [];
$hash2 = "alias";
for ($i = 0; $i < count($lines); $i++) {
    $tokens = explode("\t", $lines[$i]);
    $hash = $tokens[0];
    $map[$hash] = $i;
    if ($hash2 !== "" && strcasecmp($hash2, $hash) !== 0) {
        $map[$hash2] = $i;
    }
}

$obj = new stdClass();
$obj->x = ["y" => 1];
$obj->x["z"] = 2;

$arr = [1, 2, 3];
$copy = $arr;
$copy[1] += 10;
$arr[] = 4;

$nested = [];
$nested["k1"]["k2"] = "v";
$nested["k1"]["k3"] = ["p" => 7];

$s = "123";
$n = (int)$s;
$f = (float)"3.14159";
$b = filter_var("true", FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$t = gettype($nested);

echo count($allTokens), "\n";
echo $map["a"], " ", $map["alias"], "\n";
echo $obj->x["y"], " ", $obj->x["z"], "\n";
echo implode(",", $arr), "\n";
echo implode(",", $copy), "\n";
echo $nested["k1"]["k2"], " ", $nested["k1"]["k3"]["p"], "\n";
echo $n, " ", $f, " ", $b, " ", $t, "\n";
?>
--EXPECT--
