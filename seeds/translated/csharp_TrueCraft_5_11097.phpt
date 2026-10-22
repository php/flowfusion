--TEST--
Translated seed (inferredbugs-csharp: TrueCraft/5)
--FILE--
<?php
$clients = [];
$logProviders = [];
$packetHandlers = array_fill(0, 256, null);

$logProviders[] = function($category, $text, ...$parameters) {
    $compiled = vsprintf($text, $parameters);
};

$clients[] = (object)[
    'loggedIn' => true,
    'world' => 'w1',
    'username' => 'alice',
    'packetQueue' => new SplQueue(),
    'dataAvailable' => false,
    'stream' => fopen('php://memory', 'r+')
];
$clients[] = (object)[
    'loggedIn' => true,
    'world' => 'w2',
    'username' => 'bob',
    'packetQueue' => new SplQueue(),
    'dataAvailable' => false,
    'stream' => fopen('php://memory', 'r+')
];

$clients[0]->packetQueue->enqueue((object)['id' => 1, 'type' => 'BlockChange']);
$clients[0]->packetQueue->enqueue((object)['id' => 2, 'type' => 'Disconnect']);

for ($i = 0; $i < count($clients); $i++) {
    $client = $clients[$i];
    while (!$client->packetQueue->isEmpty()) {
        $packet = $client->packetQueue->dequeue();
        fwrite($client->stream, json_encode($packet) . "\n");
        if ($packet->type === 'Disconnect') {
            if ($client->loggedIn) {
                foreach ($logProviders as $provider) {
                    $provider('Notice', '%s has left the server.', $client->username);
                }
            }
            array_splice($clients, $i, 1);
            $i--;
            break;
        }
    }
    if ($client->dataAvailable) {
        $packet = (object)['id' => 0];
        if ($packetHandlers[$packet->id] !== null) {
            try {
                $packetHandlers[$packet->id]($packet, $client, null);
            } catch (Throwable $e) {
                foreach ($logProviders as $provider) {
                    $provider('Debug', 'Disconnecting client due to exception in network worker');
                    $provider('Debug', '%s', $e);
                }
                array_splice($clients, $i, 1);
                $i--;
            }
        }
    }
}
?>
--EXPECT--
