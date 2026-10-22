--TEST--
Translated seed (inferredbugs-csharp: spectre.console/5)
--FILE--
<?php
$decorations = [
    1 => "\033[1mHello World\033[0m",
    2 => "\033[2mHello World\033[0m",
    3 => "\033[3mHello World\033[0m",
    4 => "\033[4mHello World\033[0m",
    7 => "\033[7mHello World\033[0m",
    8 => "\033[8mHello World\033[0m",
    5 => "\033[5mHello World\033[0m",
    6 => "\033[6mHello World\033[0m",
    9 => "\033[9mHello World\033[0m",
    1 | 4 => "\033[1;4mHello World\033[0m",
    1 | 4 | 8 => "\033[1;4;8mHello World\033[0m",
];

foreach ($decorations as $codes => $expected) {
    $seq = "\033[" . str_replace('|', ';', (string)$codes) . "mHello World\033[0m";
    if ($seq !== $expected) {
        throw new Exception("Mismatch");
    }
}
?>
--EXPECT--
