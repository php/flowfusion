--TEST--
Translated seed (inferredbugs-csharp: TrueCraft/8)
--FILE--
<?php
$clients = [];
$packetHandlers = array_fill(0, 256, null);
$logProviders = [];
$worlds = [];
$entityManagers = [];

$logProviders[] = function($category, $text, $parameters) {
    $msg = vsprintf($text, $parameters);
};
$world = (object)['id' => 1];
$worlds[] = $world;
$entityManagers[] = (object)['world' => $world];

$clients[] = (object)[
    'loggedIn' => true,
    'world' => $world,
    'username' => 'alice',
    'disconnected' => false,
    'queue' => [
        ['id' => 1, 'type' => 'chat', 'text' => 'hi'],
        ['id' => 2, 'type' => 'disconnect']
    ],
    'data' => [
        ['id' => 3, 'type' => 'block', 'x' => 1, 'y' => 2, 'z' => 3, 'bid' => 4, 'meta' => 5]
    ]
];

$packetHandlers[3] = function($packet, $client, &$server) {
    $server['messages'][] = $packet['type'];
};

$server = ['messages' => []];

for ($i = 0; $i < count($clients); $i++) {
    $client = $clients[$i];
    while (!empty($client->queue)) {
        $packet = array_shift($client->queue);
        foreach ($logProviders as $provider) {
            $provider('debug', 'packet {0}', [$packet['id']]);
        }
        if ($packet['type'] === 'disconnect') {
            if ($client->loggedIn) {
                $client->disconnected = true;
            }
            break;
        }
    }
    while (!empty($client->data)) {
        $packet = array_shift($client->data);
        $handler = $packetHandlers[$packet['id']];
        if ($handler !== null) {
            $handler($packet, $client, $server);
        }
    }
    if ($client->disconnected) {
        array_splice($clients, $i, 1);
        $i--;
    }
}

echo "ok\n";
?>
--EXPECT--
