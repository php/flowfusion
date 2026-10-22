--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/6)
--FILE--
<?php
$items = [];
$items[""] = "";
$items["0"] = "0";
$items["00"] = "00";
$items["\0"] = "\0";
$items["\u{0000}x"] = "\u{0000}x";
$items["key"] = str_repeat("a", 1024);
$items[(string)PHP_INT_MAX] = "max";
$items[(string)(PHP_INT_MAX - 1)] = "max-1";

$copy = $items;
$copy[""] = $copy["0"] . $copy[""];
$copy["new"] = implode(",", array_keys($copy));
ksort($copy);

$out = [];
foreach ($copy as $k => $v) {
    $out[] = $k . "=" . $v;
}

echo implode("\n", $out), "\n";
?>
--EXPECT--
