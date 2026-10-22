--TEST--
Translated seed (inferredbugs-csharp: spectre.console/3)
--FILE--
<?php
$out = "\033[3;90;47mHello\033[0m";
$out2 = "\033[3;47mHello\033[0m";
$out3 = "\033[3;90mHello\033[0m";
$out4 = "\033[90;47mHello\033[0m";
$out5 = "\033[101mHello\033[0m\n\033[102mWorld\033[0m\n";
$out6 = "\033[101mHello\033[0m\n\033[101mWorld\033[0m\n";
echo $out, $out2, $out3, $out4, $out5, $out6;
?>
--EXPECT--
