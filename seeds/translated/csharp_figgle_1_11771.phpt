--TEST--
Translated seed (inferredbugs-csharp: figgle/1)
--FILE--
<?php
declare(strict_types=1);

$font = "flf2a@ 1 1 1 0 0\n";
for ($i = 0; $i < 95; $i++) $font .= "X@\n";
$font .= "X@\nX@\nX@\nX@\nX@\nX@\nX@\n";
$font .= "123\nX@\n";

$h = fopen('php://memory', 'r+');
fwrite($h, $font);
rewind($h);

$first = fgets($h);
if ($first === false) exit;
if (!preg_match('/^flf2a(.)\s(\d+)\s(\d+)\s(\d+)\s(-?\d+)\s(\d+)(?:\s(\d+))?(?:\s(\d+))?(?:\s(\d+))?(?:\s|$)/', rtrim($first, "\r\n"), $m)) exit;

$height = (int)$m[2];
$comment = (int)$m[6];
for ($i = 0; $i < $comment; $i++) fgets($h);

for ($i = 0; $i < 95 + 7; $i++) {
    for ($j = 0; $j < $height; $j++) {
        $line = fgets($h);
        if ($line === false) exit;
        $line = rtrim($line, "\r\n");
        $end = $line === '' ? '' : $line[strlen($line) - 1];
        if ($end !== '') $line = rtrim($line, $end);
    }
}

while (($line = fgets($h)) !== false) {
    $line = trim($line);
    if ($line === '') continue;
    if (!preg_match('/^-?\d+$/', $line)) exit;
    for ($j = 0; $j < $height; $j++) {
        $x = fgets($h);
        if ($x === false) exit;
    }
}
?>
--EXPECT--
