--TEST--
Translated seed (inferredbugs-csharp: Orang/3)
--FILE--
<?php
$input = "abc123abc";
$pattern = '/abc/';
$replacement = 'X';
$count = 0;
$result = preg_replace($pattern, $replacement, $input, -1, $count);
echo $result, PHP_EOL;
echo $count, PHP_EOL;
?>
--EXPECT--
