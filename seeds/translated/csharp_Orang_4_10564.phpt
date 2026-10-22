--TEST--
Translated seed (inferredbugs-csharp: Orang/4)
--FILE--
<?php
$input = "a1b2c3";
$pattern = '/(\d)/';
$replacement = '[$1]';
$out = preg_replace($pattern, $replacement, $input, -1, $count);
if ($out === null) {
    $out = $input;
    $count = 0;
}
echo $out, PHP_EOL, $count, PHP_EOL;
?>
--EXPECT--
