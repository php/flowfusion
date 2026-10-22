--TEST--
Translated seed (inferredbugs-csharp: cscs/39)
--FILE--
<?php
function main() {
    $a = [];
    $a["x"] = ["y" => 1];
    $b = $a;
    $b["x"]["y"]++;
    $c = $a["x"]["y"] ?? null;

    $s = "123";
    $n = (int)$s;
    $d = (float)$s;
    $t = is_bool($n > 0);

    $arr = [1, 2, 3];
    array_splice($arr, 1, 1);
    $arr[] = $c;

    $m = [];
    $m["k"] = $arr;
    $m["k"][] = $d;

    $r = json_encode($m);
    $u = json_decode($r, true);

    if (($u["k"][1] ?? null) !== $c) {
        throw new Exception("mismatch");
    }
}

main();
?>
--EXPECT--
