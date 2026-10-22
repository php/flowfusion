--TEST--
Translated seed (inferredbugs-csharp: phosphorusfive/3)
--FILE--
<?php
$source = "abc abc 123 abc";
$with = "X";
$what = "/abc/";
if (is_string($what) && strlen($what) > 2 && $what[0] === '/' && strrpos($what, '/') !== 0) {
    $pattern = $what;
    $result = preg_replace($pattern, $with, $source);
} else {
    $result = str_replace((string)$what, $with, $source);
}
if ($result === null) {
    $result = "";
}
echo $result;
?>
--EXPECT--
