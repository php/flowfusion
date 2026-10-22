--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/22)
--FILE--
<?php
$thumbWidth = 16;
$thumbHeight = 9;

$images = [
    ['w' => 1, 'h' => 100],
    ['w' => 100, 'h' => 1],
    ['w' => 16, 'h' => 9],
    ['w' => 17, 'h' => 10],
    ['w' => 8, 'h' => 8],
];

foreach ($images as $input) {
    $wRatio = $input['w'] / $thumbWidth;
    $hRatio = $input['h'] / $thumbHeight;
    $ratio = $wRatio >= $hRatio ? $wRatio : $hRatio;

    if ($input['w'] <= $thumbWidth && $input['h'] <= $thumbHeight) {
        $ratio = 1.0;
    }

    $outW = (int)min($input['w'] / $ratio, $thumbWidth);
    $outH = (int)min($input['h'] / $ratio, $thumbHeight);
    $posX = (int)($thumbWidth / 2 - $outW / 2);
    $posY = (int)($thumbHeight / 2 - $outH / 2);

    echo $input['w'] . 'x' . $input['h'] . ' -> ' . $outW . 'x' . $outH . ' @ ' . $posX . ',' . $posY . PHP_EOL;
}
?>
--EXPECT--
