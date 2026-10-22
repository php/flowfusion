--TEST--
Translated seed (inferredbugs-csharp: UniSpyServer/2)
--FILE--
<?php
$clients = [];
$processing = [];
$queue = new SplQueue();
$counter = 0;
$timeout = 20000;
$now = time();

for ($i = 0; $i < 3; $i++) {
    $id = ++$counter;
    $client = (object)[
        'ConnectionId' => $id,
        'Created' => $now - ($i === 0 ? 0 : ($timeout + 1)),
        'PlayerInfo' => (object)[
            'PlayerId' => 100 + $i,
            'PlayerNick' => 'p' . $i,
            'PlayerStatus' => $i,
            'LoginStatus' => $i === 0 ? 'Completed' : 'Pending',
        ],
        'RemoteEndPoint' => (object)['Address' => '127.0.0.' . ($i + 1)],
        'CompletedLoginProcess' => $i === 0,
        'Disposed' => false,
    ];
    $processing[$id] = $client;
    if ($i === 0) {
        unset($processing[$id]);
        $clients[$client->PlayerInfo->PlayerId] = $client;
        $queue->enqueue($client);
    }
}

foreach ($clients as $client) {
    $queue->enqueue($client);
}

foreach ($processing as $id => $client) {
    $expire = $client->Created + $timeout;
    if ($client->PlayerInfo->LoginStatus !== 'Completed' && $expire <= $now) {
        unset($processing[$id]);
    }
}

while (!$queue->isEmpty()) {
    $result = $queue->dequeue();
    if ($result === null || !$result->CompletedLoginProcess) {
        continue;
    }
    $timestamp = time();
    $addr = $result->RemoteEndPoint->Address;
    $pid = $result->PlayerInfo->PlayerId;
    $status = (int)$result->PlayerInfo->PlayerStatus;
    $sink = $timestamp . $addr . $pid . $status;
    if ($sink === '') {
        echo '';
    }
}
?>
--EXPECT--
