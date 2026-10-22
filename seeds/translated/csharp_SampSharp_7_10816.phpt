--TEST--
Translated seed (inferredbugs-csharp: SampSharp/7)
--FILE--
<?php
echo "ARGS: " . implode(" ", $argv) . PHP_EOL;
$opts = ['logLevel' => 'debug', 'hosted' => true, 'startBehaviour' => 'fakeGmx', 'gameMode' => 'GameMode'];
echo json_encode($opts, JSON_UNESCAPED_SLASHES) . PHP_EOL;
?>
--EXPECT--
