--TEST--
Translated seed (inferredbugs-csharp: SidWizPlus/1)
--FILE--
<?php
$files = [];
for ($i = 1; $i <= 9; $i++) {
    $f = tempnam(sys_get_temp_dir(), "wav");
    file_put_contents($f, str_repeat("A", 44 + $i));
    $files[] = $f;
}
$sampleLength = 64;
$allMin = PHP_FLOAT_MAX;
$allMax = -PHP_FLOAT_MAX;
$progress = 0;
$result = array_fill(0, count($files), null);
foreach ($files as $ch => $wavFilename) {
    $samples = [];
    $min = PHP_FLOAT_MAX;
    $max = -PHP_FLOAT_MAX;
    for ($i = 0; $i < $sampleLength; $i++) {
        $t = 0.0;
        for ($j = 0; $j < 2; $j++) {
            $v = (($i + $j + $ch) % 7) - 3;
            $t += $v / 32768.0;
        }
        $samples[] = $t / 2.0;
        if ($t > $max) $max = $t;
        if ($t < $min) $min = $t;
        $progress++;
    }
    if ($min == $max) continue;
    $result[$ch] = $samples;
    if ($min < $allMin) $allMin = $min;
    if ($max > $allMax) $allMax = $max;
}
$voiceData = array_values(array_filter($result, fn($ch) => $ch !== null));
$scale = 1.0 / ($allMax - $allMin);
foreach ($voiceData as &$channel) {
    foreach ($channel as &$s) $s *= $scale;
}
unset($channel, $s);
$colors = [];
for ($i = 0; $i < 9; $i++) $colors[] = $i;
$viewSamp = 1024;
$width = 320;
$height = 240;
$fps = 60;
$renderer = [
    'width' => $width,
    'height' => $height,
    'fps' => $fps,
    'samples' => $viewSamp,
    'channels' => $voiceData,
    'colors' => $colors
];
echo count($renderer['channels']) . "\n";
?>
--EXPECT--
