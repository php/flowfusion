--TEST--
Translated seed (inferredbugs-csharp: Orang/2)
--FILE--
<?php
$input = "abc123abc";
$pattern = '/(abc)(\d+)(abc)/';
$replacement = '$3-$2-$1';
if (preg_match($pattern, $input, $m, PREG_OFFSET_CAPTURE)) {
    $out = preg_replace($pattern, $replacement, $input, 1);
    echo $out, PHP_EOL;
    echo count($m), PHP_EOL;
    echo $m[1][0], PHP_EOL;
}
?>
--EXPECT--
