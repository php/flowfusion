--TEST--
Translated seed (inferredbugs-csharp: pinvoke-interop-assistant/2)
--FILE--
<?php
$buf = str_repeat("A", 8);
$size = 8;
for ($i = 0; $i < 4; $i++) {
    $old = $size;
    $size *= 2;
    $tmp = str_repeat("B", $size);
    if ($old <= $size) {
        $buf = $tmp;
    }
}
$ptr = [$buf, $size];
$pp = [&$ptr];
$pp[0][1] += 1;
echo $pp[0][0] . $pp[0][1];
?>
--EXPECT--
