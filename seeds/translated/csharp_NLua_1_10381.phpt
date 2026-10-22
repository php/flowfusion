--TEST--
Translated seed (inferredbugs-csharp: NLua/1)
--FILE--
<?php
$s = "a(b)c";
echo substr($s, 1, -1), "\n";
echo strrev($s), "\n";
echo strtolower($s), "\n";
echo strtoupper($s), "\n";
echo str_repeat("ab", 3), "\n";
echo implode(",", array_map('ord', str_split(substr("xyz", 1, 2)))), "\n";
echo chr(65) . chr(0) . chr(255), "\n";
echo sprintf("%q", "x"), "\n";
preg_match('/^(a+)(b*)$/', "aaab", $m);
echo $m[1], ":", $m[2], "\n";
echo preg_replace('/a/', '[$0]', 'banana', 2), "\n";
echo preg_replace_callback('/\d+/', function($m){ return (string)((int)$m[0] + 1); }, "a9b99"), "\n";
echo preg_match('/\b\w+\b/', " hi "), "\n";
?>
--EXPECT--
