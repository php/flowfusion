--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/19)
--FILE--
<?php
$vals = [null, 0, 1, "1", "01", "foo", [], [1], true, false, 1.5, "1e2", "0e123", " 2 "];
$sum = 0;
foreach ($vals as $v) {
    $sum += (int)$v;
    $a = [$v => 1];
    $b = array_key_exists((string)$v, $a) ? $a[(string)$v] : 0;
    $c = in_array($v, $vals, true) ? 1 : 0;
    $d = json_encode($v);
    $e = strlen((string)$d);
    $sum += $b + $c + $e;
}
$nested = ["x" => ["y" => ["z" => 1]], "n" => null];
$ref = &$nested["x"]["y"];
$ref["z"] += $sum;
foreach ($nested as $k => $v) {
    if (is_array($v)) {
        foreach ($v as $k2 => $v2) {
            if (is_array($v2)) {
                foreach ($v2 as $k3 => $v3) {
                    $sum += $v3;
                }
            }
        }
    }
}
echo $sum, "\n";
?>
--EXPECT--
