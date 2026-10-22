--TEST--
Translated seed (inferredbugs-csharp: UniSpyServer/1)
--FILE--
<?php
$clients = [];
$processing = [];
$queue = [];

$now = time();
$timeout = 20;

$makeClient = function ($id, $status, $createdOffset, $pid, $nick) use ($now) {
    return [
        'ConnectionId' => $id,
        'Created' => $now - $createdOffset,
        'PlayerInfo' => [
            'PlayerId' => $pid,
            'PlayerNick' => $nick,
            'PlayerStatus' => $status,
            'LoginStatus' => $status === 1 ? 'Completed' : 'Pending',
        ],
        'RemoteEndPoint' => ['Address' => '127.0.0.1'],
        'CompletedLoginProcess' => $status === 1,
        'Disposed' => false,
    ];
};

$processing[1] = $makeClient(1, 0, 30, 1001, 'alpha');
$processing[2] = $makeClient(2, 1, 5, 1002, 'beta');
$clients[1002] = $processing[2];

$checkTimeout = function (&$client) use (&$processing, $now, $timeout) {
    if ($client['PlayerInfo']['LoginStatus'] !== 'Completed' && ($client['Created'] + $timeout) <= $now) {
        unset($processing[$client['ConnectionId']]);
    }
};

foreach ($clients as $client) {
    $queue[] = $client;
}
foreach ($processing as &$client) {
    $checkTimeout($client);
}
unset($client);

while ($queue) {
    $client = array_shift($queue);
    if ($client === null || !$client['CompletedLoginProcess']) {
        continue;
    }
    $clients[$client['PlayerInfo']['PlayerId']] = $client;
}

if (isset($clients[1002])) {
    unset($clients[1002]);
}

$queue[] = $makeClient(3, 1, 0, 1003, 'gamma');
$queue[] = null;
$queue[] = $makeClient(4, 0, 100, 1004, 'delta');

while ($queue) {
    $client = array_shift($queue);
    if ($client === null || !$client['CompletedLoginProcess']) {
        continue;
    }
    $clients[$client['PlayerInfo']['PlayerId']] = $client;
}

echo count($clients), "\n";
?>
--EXPECT--
