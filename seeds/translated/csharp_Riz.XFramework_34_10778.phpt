--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/34)
--FILE--
<?php
$s = ["", "abc", "x", "hello", "  trim  ", "a|b|c"];
foreach ($s as $v) {
    $r = $v ?? "TAN";
    $t = is_string($v) ? $v : (string)$v;
    $u = substr($t, 1);
    $w = strpos($t, "b");
    $x = str_contains($t, "a");
    $y = str_starts_with($t, "h");
    $z = str_ends_with($t, "c");
    $n = strlen($t);
    $c = $t . $r . $u . $w . ($x ? "1" : "0") . ($y ? "1" : "0") . ($z ? "1" : "0") . $n;
    echo $c, "\n";
}
$g = bin2hex(random_bytes(16));
echo $g, "\n";
echo date("Y-m-d H:i:s"), "\n";
echo gmdate("Y-m-d H:i:s"), "\n";
echo trim("  trim  "), "\n";
echo substr("abcdef", 1, 3), "\n";
echo implode(" || ", array_map("strval", ["a", 1, 2.5])), "\n";
?>
--EXPECT--
