--TEST--
Translated seed (inferredbugs-csharp: pinvoke-interop-assistant/5)
--FILE--
<?php
$buf = str_repeat("x", 4);
$size = 4;
$old = 0;
for ($i = 0; $i < 6; $i++) {
    $old = $size;
    $buf = str_repeat("x", $size);
    $ret = $size;
    if ($old <= $size) {
        $size *= 2;
        continue;
    }
    $buf = substr($buf, 0, $ret);
    break;
}
echo strlen($buf), "\n";
?>
--EXPECT--
