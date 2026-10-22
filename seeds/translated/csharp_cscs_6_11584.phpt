--TEST--
Translated seed (inferredbugs-csharp: cscs/6)
--FILE--
<?php
$v = [];
$v["x"] = 1;
$v["x"] += 2;
$v["s"] = "a";
$v["s"] .= "b";
$v["arr"] = [];
$v["arr"][3] = 7;
$v["arr"]["k"] = 9;
$v["arr"][1][2] = 5;
$v["arr"][1][2] += 4;
$v["n"] = 0;
for ($i = 0; $i < 3; $i++) {
    if ($i === 1) continue;
    if ($i === 2) break;
    $v["n"]++;
}
try {
    if (isset($v["arr"]["k"])) {
        throw new Exception((string)$v["arr"]["k"]);
    }
} catch (Throwable $e) {
    $v["e"] = $e->getMessage();
}
function f($a, $b) {
    $r = [$a, $b];
    $r[0] .= "x";
    return $r;
}
$v["t"] = f("p", "q");
$v["type"] = gettype($v["arr"]);
$v["size"] = count($v["arr"]);
echo "";
?>
--EXPECT--
