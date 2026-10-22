--TEST--
Translated seed (inferredbugs-csharp: pusher-http-dotnet/1)
--FILE--
<?php
$payload = ["hello" => "world", "percent" => "100%", "nested" => ["a" => 1, "b" => str_repeat("x", 84)]];
$channels = ["my-channel", "my-channel-1", "my-channel-2", "ch1", "ch2", "ch3"];
$events = [];
for ($i = 0; $i < 9; $i++) $events[] = ["channel" => $channels[$i % 3], "name" => "my_event", "data" => str_repeat("a", 84)];
$events[] = ["channel" => "my-channel", "name" => "my_event", "data" => str_repeat("b", 10241)];
$json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$decoded = json_decode($json, true);
$multi = array_map(fn($c) => ["channel" => $c, "name" => "my_event", "data" => $decoded], ["my-channel-1", "my-channel-2"]);
$batch = array_merge($events, [["channel" => "my-channel", "name" => "my_event", "data" => $decoded]]);
$batchJson = json_encode($batch, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo strlen($json) . "\n";
echo count($multi) . "\n";
echo count($batch) . "\n";
echo substr($batchJson, 0, 1) . substr($batchJson, -1) . "\n";
?>
--EXPECT--
