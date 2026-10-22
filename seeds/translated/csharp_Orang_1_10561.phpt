--TEST--
Translated seed (inferredbugs-csharp: Orang/1)
--FILE--
<?php
$input = "a1 b2 c3";
$pattern = '/(\w)(\d)/';
$replacement = '$2$1';
$result = preg_replace_callback($pattern, function ($m) use ($replacement) {
    return preg_replace('/\$(\d)/', function ($g) use ($m) {
        $i = (int)$g[1];
        return $m[$i] ?? '';
    }, $replacement);
}, $input);
echo $result, PHP_EOL;
?>
--EXPECT--
