--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/3)
--FILE--
<?php
$includes = [];
$diskCache = [];

$includes['a'] = "header_a\n";
$includes['b'] = "header_b\n";

$code = "kernel_body";
$prependIncludes = ['a', 'b'];

$finalCode = '';
foreach ($prependIncludes as $includeName) {
    $finalCode .= $includes[$includeName] . "\n";
}
$finalCode .= $code;

$key = md5($finalCode, true);
if (!array_key_exists($key, $diskCache)) {
    $diskCache[$key] = strlen($finalCode);
}

echo $diskCache[$key];
?>
--EXPECT--
