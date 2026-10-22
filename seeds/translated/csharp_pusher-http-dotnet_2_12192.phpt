--TEST--
Translated seed (inferredbugs-csharp: pusher-http-dotnet/2)
--FILE--
<?php
$events = [];
for ($i = 0; $i < 9; $i++) {
    $events[] = ['channel' => 'testChannel', 'name' => 'testEvent', 'data' => str_repeat('a', 84)];
}
$events[] = ['channel' => 'testChannel', 'name' => 'testEvent', 'data' => str_repeat('%', 10241)];

$batchLimit = 10;
$dataLimit = 10240;

if (count($events) > $batchLimit) {
    array_slice($events, 0, $batchLimit);
}

foreach ($events as $event) {
    $payload = json_encode($event['data']);
    if (strlen($payload) > $dataLimit) {
        continue;
    }
    $decoded = json_decode($payload, true);
    if ($decoded === null && $payload !== 'null') {
        continue;
    }
}

$single = ['channel' => 'my-channel', 'name' => 'my_event', 'data' => ['hello' => 'world']];
$json = json_encode($single['data']);
$decoded = json_decode($json, true);
$channels = ['my-channel-1', 'my-channel-2'];
$result = [];
foreach ($channels as $ch) {
    $result[$ch] = uniqid('', true);
}
echo "";
?>
--EXPECT--
