--TEST--
Translated seed (inferredbugs-csharp: python-language-server/17)
--FILE--
<?php
$doc = "def f(x):\n    return\n\ndef g(y):\n    return y * 2\n";
$doc = substr($doc, 0, 31) . "g(x)" . substr($doc, 31);
$doc = substr($doc, 0, 38) . "x * 2" . substr($doc, 39);
$doc = substr($doc, 0, 42) . "300" . substr($doc, 43);
$doc = substr($doc, 0, 7) . "#" . substr($doc, 13);
$doc = "";
$doc = "text";
$doc = "abcdef";
$doc = "text";
$doc = "text";
$doc = "abcdef";
$doc = "";
$doc = "text";
$doc = "1234";
$doc = "text1234";
echo $doc;
?>
--EXPECT--
