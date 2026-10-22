--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/24)
--FILE--
<?php
$thumbWidth = 3;
$thumbHeight = 5;

$images = [
    ['w' => 1, 'h' => 1],
    ['w' => 2, 'h' => 10],
    ['w' => 10, 'h' => 2],
    ['w' => 3, 'h' => 5],
    ['w' => 1000000000, 'h' => 1],
];

foreach ($images as $input) {
    $wRatio = $input['w'] / $thumbWidth;
    $hRatio = $input['h'] / $thumbHeight;
    $ratio = $wRatio >= $hRatio ? $wRatio : $hRatio;

    if ($input['w'] <= $thumbWidth && $input['h'] <= $thumbHeight) {
        $ratio = 1.0;
    }

    $sizeW = (int)min($input['w'] / $ratio, $thumbWidth);
    $sizeH = (int)min($input['h'] / $ratio, $thumbHeight);
    $posX = (int)($thumbWidth / 2 - $sizeW / 2);
    $posY = (int)($thumbHeight / 2 - $sizeH / 2);

    $canvas = array_fill(0, $thumbHeight, array_fill(0, $thumbWidth, 0));
    for ($y = 0; $y < $sizeH; $y++) {
        for ($x = 0; $x < $sizeW; $x++) {
            $cx = $posX + $x;
            $cy = $posY + $y;
            if ($cx >= 0 && $cx < $thumbWidth && $cy >= 0 && $cy < $thumbHeight) {
                $canvas[$cy][$cx] = 1;
            }
        }
    }
}

echo "ok\n";
?>
--EXPECT--
