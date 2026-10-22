--TEST--
Translated seed (inferredbugs-csharp: cscs/97)
--FILE--
<?php
$u = "\u{FFFD}";
$a = [1, 2, 3];
$b = &$a;
$b[1] = 99;
$s = "123";
$n = (int)$s;
$f = (float)"INF";
$g = is_nan(NAN);
$h = is_finite($f);
$x = ["k" => ["z" => 7]];
$y = $x["k"]["z"];
$e = ["A" => 0, "B" => 1];
$z = $e["B"];
$mix = [];
$mix[] = $a;
$mix[] = $x;
$mix[] = $e;
$mix[] = ["nan" => NAN, "inf" => INF, "ninf" => -INF];
$copy = $mix;
$copy[0][0] = 42;
$copy[1]["k"]["z"] = 8;
$copy[2]["B"] = 2;
$copy[3]["nan"] = $copy[3]["nan"] + 1;
$copy[3]["inf"] = $copy[3]["inf"] - 1;
$copy[3]["ninf"] = $copy[3]["ninf"] + 1;
echo json_encode([$a, $b, $s, $n, $f, $g, $h, $y, $z, $copy], JSON_PARTIAL_OUTPUT_ON_ERROR), PHP_EOL;
?>
--EXPECT--
