--TEST--
Translated seed (inferredbugs-csharp: RaspberryPi.Net/1)
--FILE--
<?php
declare(strict_types=1);

$exportedPins = [];
$pin = 1;

$createPin = function(int $pin, string $dir) use (&$exportedPins) {
    if (isset($exportedPins[$pin])) {
        if ($exportedPins[$pin]['dir'] !== $dir) {
            $exportedPins[$pin]['dir'] = $dir;
        }
        return $exportedPins[$pin];
    }
    $exportedPins[$pin] = ['pin' => $pin, 'dir' => $dir, 'disposed' => false];
    return $exportedPins[$pin];
};

$write = function(int $pin, bool $value) use (&$createPin) {
    $p = $createPin($pin, 'out');
    $p['value'] = $value;
};

$read = function(int $pin) use (&$createPin) {
    $p = $createPin($pin, 'in');
    return $p['value'] ?? false;
};

$p1 = $createPin($pin, 'out');
$p2 = $createPin($pin, 'out');

$write($pin, true);
$read($pin);

unset($exportedPins[$pin]);
$createPin($pin, 'in');
$read($pin);
?>
--EXPECT--
