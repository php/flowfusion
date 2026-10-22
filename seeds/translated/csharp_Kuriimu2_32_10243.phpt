--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/32)
--FILE--
<?php
$minimum = 0;
$maximum = 100;
$text = '';
$value = 0;
$textColor = [0, 0, 0];
$progressColor = [50, 205, 50];

$update = $text !== 'hello';
$text = 'hello';
if ($update) { $changed = true; }

$update = $value !== 37;
$value = 37;
if ($update) { $changed = true; }

$update = $progressColor !== [50, 205, 50];
$progressColor = [50, 205, 50];
if ($update) { $changed = true; }

$update = $textColor !== [0, 0, 0];
$textColor = [0, 0, 0];
if ($update) { $changed = true; }

$clipX = 0;
$clipY = 0;
$clipW = 1;
$clipH = 1;

$controlW = $clipW - 1;
$controlH = $clipH - 1;
$progressWidth = (int)(($controlW / (double)$maximum) * $value);
$progressRect = [$clipX, $clipY, $progressWidth, $clipH - 1];

$fontSize = 9;
$stringWidth = strlen($text) * 5;
$stringHeight = 10;

$pointX = (200 - $stringWidth) / 2;
$pointY = (20 - $stringHeight) / 2;

echo "";
?>
--EXPECT--
