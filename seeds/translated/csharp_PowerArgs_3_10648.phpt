--TEST--
Translated seed (inferredbugs-csharp: PowerArgs/3)
--FILE--
<?php
$w = 75;
$h = 20;
$grid = array_fill(0, $h, array_fill(0, $w, ' '));
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        if ($x === 0 || $x === $w - 1 || $y === 0 || $y === $h - 1) {
            $grid[$y][$x] = '#';
        }
    }
}
for ($y = 0; $y < $h; $y++) {
    echo implode('', $grid[$y]), PHP_EOL;
}
?>
--EXPECT--
