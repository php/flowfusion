--TEST--
Translated seed (inferredbugs-csharp: NuklearDotNet/1)
--FILE--
<?php
$blendState = [
    'ColorBlendFunction' => 'Add',
    'ColorSourceBlend' => 'SourceAlpha',
    'ColorDestinationBlend' => 'InverseSourceAlpha',
];

$values = [
    0.0,
    -0.0,
    1.0,
    -1.0,
    INF,
    -INF,
    NAN,
    PHP_FLOAT_MAX,
    PHP_FLOAT_MIN,
    1.0 / 3.0,
];

$acc = 0.0;
foreach ($values as $v) {
    $acc += is_nan($v) ? 0.0 : $v;
    $blendState['last'] = $v;
    $blendState['mix'] = $acc * 0.5 + (is_finite($v) ? $v : 0.0);
}

$elapsed = microtime(true);
$delta = fmod($elapsed, 1.0);
$result = $delta + $acc;

if (is_nan($result)) {
    $result = 0.0;
}

echo "";
?>
--EXPECT--
