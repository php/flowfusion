--TEST--
Translated seed (inferredbugs-csharp: pinvoke-interop-assistant/3)
--FILE--
<?php
$buf = str_repeat("x", 4);
$size = 4;
for ($i = 0; $i < 3; $i++) {
    $old = $size;
    if ($size <= strlen($buf)) {
        $size *= 2;
        $buf = str_repeat("x", $size);
        continue;
    }
    $buf = substr($buf, 0, $size);
    break;
}
$ptr = &$buf;
$ptr .= "y";
echo strlen($buf), "\n";
?>
--EXPECT--
