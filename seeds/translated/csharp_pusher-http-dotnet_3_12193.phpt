--TEST--
Translated seed (inferredbugs-csharp: pusher-http-dotnet/3)
--FILE--
<?php
$payload = ['hello' => 'world', 'percent' => '100%'];
$json = json_encode($payload, JSON_UNESCAPED_SLASHES);
$decoded = json_decode($json, true);
if (!is_array($decoded) || $decoded['percent'] !== '100%') {
    exit(1);
}

$channels = ['my-channel-1', 'my-channel-2', 'ch1', 'ch2', 'ch3'];
$events = [];
foreach ($channels as $ch) {
    $events[$ch] = ['my_event', $decoded];
}

$batch = [];
for ($i = 0; $i < 9; $i++) {
    $batch[] = ['channel' => 'testChannel', 'name' => 'testEvent', 'data' => str_repeat('a', 84)];
}
$batch[] = ['channel' => 'testChannel', 'name' => 'testEvent', 'data' => str_repeat('b', 10241)];

$single = ['channel' => 'my-channel', 'name' => 'my_event', 'data' => $decoded];
$multi = [['channel' => 'my-channel-1', 'name' => 'my_event', 'data' => $decoded], ['channel' => 'my-channel-2', 'name' => 'my_event', 'data' => $decoded]];

$sizeOk = strlen(json_encode(array_slice($batch, 0, 9))) <= 10240;
$sizeTooBig = strlen(json_encode($batch)) > 10240;
$countTooBig = count(array_merge($batch, [['channel' => 'x', 'name' => 'y', 'data' => 'z']])) > 10;

if (!$sizeOk || !$sizeTooBig || !$countTooBig || empty($single) || empty($multi) || empty($events)) {
    exit(1);
}
?>
--EXPECT--
