--TEST--
Translated seed (inferredbugs-csharp: SharpAudio/4)
--FILE--
<?php
$engine = null;
$buffer = [];
$source = [];
$samples = [];
$format = ['sampleRate' => 44100, 'channels' => 1, 'bitsPerSample' => 16];

$durationSeconds = 1.0;
$sampleRate = $format['sampleRate'];
$sampleCount = (int)($sampleRate * $durationSeconds);
$frequency = 440.0;

for ($i = 0; $i < $sampleCount; $i++) {
    $samples[] = (int)(sin(2.0 * M_PI * $frequency * $i / $sampleRate) * 32767);
}

$buffer['data'] = $samples;
$buffer['format'] = $format;

$source['queue'] = [];
$source['queue'][] = $buffer;
$source['playing'] = true;

usleep(1000000);
?>
--EXPECT--
