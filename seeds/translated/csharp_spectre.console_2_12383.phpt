--TEST--
Translated seed (inferredbugs-csharp: spectre.console/2)
--FILE--
<?php
$out = "\033[3;90;47mHello\033[0m";
if ($out !== "\033[3;90;47mHello\033[0m") exit(1);

$out = "\033[3;47mHello\033[0m";
if ($out !== "\033[3;47mHello\033[0m") exit(1);

$out = "\033[3;90mHello\033[0m";
if ($out !== "\033[3;90mHello\033[0m") exit(1);

$out = "\033[90;47mHello\033[0m";
if ($out !== "\033[90;47mHello\033[0m") exit(1);

$out = "\033[101mHello\033[0m\n\033[102mWorld\033[0m\n";
if ($out !== "\033[101mHello\033[0m\n\033[102mWorld\033[0m\n") exit(1);

$out = "\033[101mHello\033[0m\n\033[101mWorld\033[0m\n";
if ($out !== "\033[101mHello\033[0m\n\033[101mWorld\033[0m\n") exit(1);
?>
--EXPECT--
