--TEST--
Translated seed (inferredbugs-csharp: resin/15)
--FILE--
<?php
$words = ["baby","bad","bank","box","dad","daddy","dance","dancing"];
file_put_contents("0.bt", implode("\r\n", array_map(fn($w) => $w, $words)) . "\r\n");

$data = file("0.bt", FILE_IGNORE_NEW_LINES);
$has = fn($w) => in_array($w, $data, true);
$starts = fn($p) => array_values(array_filter($data, fn($w) => str_starts_with($w, $p)));
$near = fn($q, $d) => array_values(array_filter($data, fn($w) => levenshtein($q, $w) <= $d));

$near("bazy", 1);
$near("bazy", 2);
$starts("ba");
$has("baby");
$has("bab");
$has("xxx");
$starts("ba");
$near("ba", 1);
$near("ba", 2);
$near("ba", 0);
$near("ba", 6);
$near("bazy", 3);
?>
--EXPECT--
