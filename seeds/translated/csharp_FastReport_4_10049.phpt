--TEST--
Translated seed (inferredbugs-csharp: FastReport/4)
--FILE--
<?php
$w = 33;
$h = 33;
$m = [];
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $m[$y][$x] = (($x * 3 + $y * 5 + (($x ^ $y) & 7)) % 11) < 5 ? 0 : 1;
    }
}
$px = 4;
$kx = 1.25;
$ky = 0.75;
$showText = true;
$textAdd = $showText ? 18 : 0;
$boundsW = $w * $px;
$boundsH = $h * $px + $textAdd;
$dark = [];
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        if ($m[$y][$x] === 0) {
            $dark[] = [$x * $px * $kx, $y * $px * $ky, $px * $kx, $px * $ky];
        }
    }
}
echo $boundsW . "x" . $boundsH . ":" . count($dark) . PHP_EOL;
?>
--EXPECT--
