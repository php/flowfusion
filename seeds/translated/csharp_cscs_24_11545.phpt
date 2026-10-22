--TEST--
Translated seed (inferredbugs-csharp: cscs/24)
--FILE--
<?php
$a = [];
$a[] = "x";
$a["k"] = "v";
$a[] = "y";
$a["k"] = "w";
$b = $a;
$b[] = "z";
$b["n"] = "m";
$c = [];
$c["0"] = 1;
$c[0] = 2;
$c["01"] = 3;
$c[] = 4;
$d = ["1" => "a", 1 => "b", "1.0" => "c", 1.0 => "d"];
$e = ["p" => ["q" => [1, 2, 3]]];
$e["p"]["q"][] = 4;
$e["p"]["r"] = ["s" => 5];
$f = array_merge($a, $b, $c, $d);
$g = array_values($f);
$h = array_keys($e["p"]);
$i = count($e["p"]["q"]);
$j = isset($e["p"]["r"]["s"]) ? $e["p"]["r"]["s"] : null;
$k = array_key_exists("k", $a) ? $a["k"] : null;
$l = $a["k"] ?? null;
$m = $c["0"] + $c[0] + $c["01"];
$n = implode(",", $g);
$o = json_encode([$a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n]);
echo $o, PHP_EOL;
?>
--EXPECT--
