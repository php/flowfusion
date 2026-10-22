--TEST--
Translated seed (inferredbugs-csharp: python-language-server/7)
--FILE--
<?php
$text = "x = 1";
$offset = 5;
$line = 100;
$column = 3;

$tokens = preg_split('/(\s+|=)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
$pos = $offset;

foreach ($tokens as $tok) {
    if (ctype_space($tok)) {
        $pos += strlen($tok);
        $column += strlen($tok);
        continue;
    }
    echo $pos . ":" . strlen($tok) . ":" . $line . ":" . $column . PHP_EOL;
    $pos += strlen($tok);
    $column += strlen($tok);
}
?>
--EXPECT--
