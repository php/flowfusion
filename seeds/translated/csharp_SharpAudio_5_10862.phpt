--TEST--
Translated seed (inferredbugs-csharp: SharpAudio/5)
--FILE--
<?php
$samples = array_fill(0, 44100, 0);
$buffer = $samples;
$leftSource = ['queued' => $buffer, 'pos' => -10, 'playing' => false];
$rightSource = ['queued' => $buffer, 'pos' => 10, 'playing' => false];
$listener = 0;
$leftSource['playing'] = true;
usleep(1500000);
$rightSource['playing'] = true;
usleep(1500000);
?>
--EXPECT--
