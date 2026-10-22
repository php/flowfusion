--TEST--
Translated seed (inferredbugs-csharp: python-language-server/5)
--FILE--
<?php
$items = [1, 2, 3];
$items[] = 4;
$s = "123.45";
$pos = strpos($s, ".") + 1;
$x = (float)substr($s, 0, $pos) + (float)substr($s, $pos);
$y = "a" . $x;
$z = ["k" => $y, "n" => null];
foreach ($items as $i) {
    $z["k"] .= $i;
}
if (isset($z["n"]) || is_string($z["k"])) {
    echo $z["k"], "\n";
}
?>
--EXPECT--
