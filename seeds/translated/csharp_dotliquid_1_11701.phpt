--TEST--
Translated seed (inferredbugs-csharp: dotliquid/1)
--FILE--
<?php
$source = str_repeat("x", 1000) . "{{-a-}}" . str_repeat("y", 1000) . "{%-b-%}" . str_repeat("z", 1000);
$source = preg_replace('/([ \t]+)?(\{\{|\{\%)-/', '$2', $source);
$source = preg_replace('/-(\}\}|\%\})(\n|\r\n|[ \t]+)?/', '$1', $source);
$tokens = preg_split('/(\{\{.*?\}\}|\{\%.*?\%\})/', $source, -1, PREG_SPLIT_DELIM_CAPTURE);
for ($i = count($tokens) - 1; $i > 0; --$i) {
    if ($tokens[$i] === '') {
        array_splice($tokens, $i, 1);
    }
}
if (isset($tokens[0]) && $tokens[0] === '') {
    array_shift($tokens);
}
echo count($tokens), "\n";
?>
--EXPECT--
