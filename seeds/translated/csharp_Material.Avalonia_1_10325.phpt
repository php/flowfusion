--TEST--
Translated seed (inferredbugs-csharp: Material.Avalonia/1)
--FILE--
<?php
$classes = ['notransitions'];
$running = false;
$boundsWidth = 120.0;
$boundsHeight = 80.0;
$circle = (object)['Opacity' => 1.0, 'Width' => 0.0, 'Margin' => [0.0, 0.0, 0.0, 0.0]];
$pointer = (object)['X' => 15.5, 'Y' => 22.25];

if (in_array('notransitions', $classes, true)) {
    $circle->Opacity = 0;
    $circle->Width = 0;
}

if (!$running) {
    if (in_array('notransitions', $classes, true)) {
        $circle->Opacity = 1;
        $circle->Width = max($boundsWidth, $boundsHeight) * 2.2;
        $circle->Margin = [$pointer->X - $circle->Width / 2, $pointer->Y - $circle->Width / 2, 0, 0];
    } else {
        $running = true;
        $maxWidth = max($boundsWidth, $boundsHeight) * 2.2;
        $circle->Width = $maxWidth;
        $circle->Margin = [$pointer->X, $pointer->Y, 0, 0];
        $circle->Opacity = 0;
        $circle->Opacity = 0;
        $running = false;
    }
}

echo "";
?>
--EXPECT--
