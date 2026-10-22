--TEST--
Translated seed (inferredbugs-csharp: Jaya/2)
--FILE--
<?php
$values = [null, false, true, 0, 1, -1, 2147483647, -2147483648, 9223372036854775807, "0", "1", "", "foo", [], [1], (object)[], fopen("php://memory", "r+")];
foreach ($values as $v) {
    $inverse = !$v;
    $showInTaskbar = $inverse;
    $windowStartupLocation = $inverse ? "CenterScreen" : "CenterOwner";
    $isVisible = $inverse;
    $state = $v === "maximized" ? "normal" : "maximized";
    $state = $state === "maximized" ? "normal" : "maximized";
    $s = serialize([$v, $inverse, $showInTaskbar, $windowStartupLocation, $isVisible, $state]);
    unserialize($s);
}
$h = fopen("php://memory", "r+");
fwrite($h, str_repeat("A", 1));
rewind($h);
stream_get_contents($h);
fclose($h);
?>
--EXPECT--
