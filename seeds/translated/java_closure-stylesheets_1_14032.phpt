--TEST--
Translated seed (inferredbugs-java: closure-stylesheets/1)
--FILE--
<?php

$functions = [
    'add' => function($a, $b) { return $a + $b; },
    'sub' => function($a, $b) { return $a - $b; },
    'mult' => function($a, $b) { return $a * $b; },
    'divide' => function($a, $b) { return $b !== 0 ? $a / $b : null; },
    'min' => function(...$values) { return min($values); },
    'max' => function(...$values) { return max($values); },
    'blendColors' => function($color1, $color2) { return (hexdec($color1) + hexdec($color2)) / 2; },
    'blendColorsRgb' => function($r1, $g1, $b1, $r2, $g2, $b2) { return [($r1 + $r2) / 2, ($g1 + $g2) / 2, ($b1 + $b2) / 2]; },
    'makeMutedColor' => function($color) { return (hexdec($color) & 0x7F7F7F); },
    'addHsbToCssColor' => function($color, $h, $s, $b) { return $color; }, // Mock implementation
    'makeContrastingColor' => function($color) { return ($color ^ 0xFFFFFF); },
    'addToNumericValue' => function($value, $addend) { return $value + $addend; },
    'subtractFromNumericValue' => function($value, $subtrahend) { return $value - $subtrahend; },
    'adjustBrightness' => function($color, $amount) { return $color; }, // Mock implementation
    'selectFrom' => function($array, $condition) { return array_filter($array, $condition); },
];

$a = 5;
$b = 10;
$result = $functions['add']($a, $b);
$result = $functions['sub']($result, 3);
$result = $functions['mult']($result, 2);
$result = $functions['divide']($result, 2);
$result = $functions['min']($result, 1, 2, 3);
$result = $functions['max']($result, 9, 8, 7);

$colorResult = $functions['blendColors']('FF0000', '00FF00');
$mutedColor = $functions['makeMutedColor']('FF00FF');

$numericResult = $functions['addToNumericValue'](100, 50);
$numericResult = $functions['subtractFromNumericValue']($numericResult, 30);

$brightnessAdjusted = $functions['adjustBrightness']('FFFFFF', 10);

$selectedValues = $functions['selectFrom']([1, 2, 3, 4, 5], fn($x) => $x > 2);
--EXPECT--
