--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/35)
--FILE--
<?php
$rows = [];
$rows[] = ["size" => [-1, 200]];
$rows[] = ["text" => "Toggle codes", "command" => null];
$rows[] = ["text" => "Save", "command" => null];

$layout = ["rows" => $rows];

echo count($layout["rows"]) . PHP_EOL;
?>
--EXPECT--
