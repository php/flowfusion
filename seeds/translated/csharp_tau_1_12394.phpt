--TEST--
Translated seed (inferredbugs-csharp: tau/1)
--FILE--
<?php
$width = 128;
$borderThickness = 15;
$image = array_fill(0, $width, array_fill(0, $width, [255, 255, 255, 63]));
for ($i = 0; $i < $width; $i++) {
    for ($j = 0; $j < $width; $j++) {
        $brightness = 63;
        if ($j < $borderThickness || $j > ($width - $borderThickness)) {
            $brightness = 255;
        }
        $image[$i][$j][3] = $brightness;
    }
}
$sum = 0;
for ($i = 0; $i < $width; $i++) {
    for ($j = 0; $j < $width; $j++) {
        $sum += $image[$i][$j][3];
    }
}
echo $sum, PHP_EOL;
?>
--EXPECT--
