--TEST--
Translated seed (inferredbugs-csharp: RPGCore/1)
--FILE--
<?php
$board = [];
$board["building"] = ["orientation" => 0, "x" => 0, "y" => 0];

for ($i = 0; $i < 4; $i++) {
    $board["building"]["orientation"] = ($board["building"]["orientation"] + 90) % 360;
}

echo $board["building"]["orientation"], PHP_EOL;
--EXPECT--
