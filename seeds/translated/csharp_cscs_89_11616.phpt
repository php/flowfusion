--TEST--
Translated seed (inferredbugs-csharp: cscs/89)
--FILE--
<?php
$cases = [
    ["1", "2", "3"],
    ["1", "x", "3"],
    ["a", "b", "c"],
];
foreach ($cases as $case) {
    $v = [];
    foreach ($case as $i => $x) {
        $v[$i] = $x;
    }
    $v[] = "tail";
    array_splice($v, 1, 1);
    $v[1] = $v[1] . "";
    $v[0] = (string)((int)$v[0] + 1);
    $v["k"] = $v[0];
    isset($v["k"]);
    in_array("tail", $v, true);
    count($v);
}
echo "ok\n";
?>
--EXPECT--
