--TEST--
Translated seed (inferredbugs-csharp: QuestPDF/2)
--FILE--
<?php
$minWidth = 5.0;
$maxWidth = 12.0;
$minHeight = 3.0;
$maxHeight = 8.0;

$availableWidth = 10.0;
$availableHeight = 6.0;

if ($minWidth > $availableWidth + PHP_FLOAT_EPSILON) {
    exit(0);
}
if ($minHeight > $availableHeight + PHP_FLOAT_EPSILON) {
    exit(0);
}

$availableWidth = min($maxWidth, $availableWidth);
$availableHeight = min($maxHeight, $availableHeight);

$child = function (float $w, float $h) {
    if ($w < 0.0 || $h < 0.0) {
        return null;
    }
    return [$w * 0.5, $h * 0.5, 'full'];
};

$measurement = $child($availableWidth, $availableHeight);
if ($measurement === null) {
    exit(0);
}

[$w, $h, $kind] = $measurement;
$w = max($minWidth, $w);
$h = max($minHeight, $h);

if ($kind === 'full') {
    $result = [$w, $h, 'full'];
} elseif ($kind === 'partial') {
    $result = [$w, $h, 'partial'];
} else {
    $result = [$w, $h, 'unknown'];
}

$drawWidth = min($maxWidth, $availableWidth);
$drawHeight = min($maxHeight, $availableHeight);
$child($drawWidth, $drawHeight);

echo $result[0] . "," . $result[1] . "," . $result[2] . "\n";
--EXPECT--
