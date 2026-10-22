--TEST--
Translated seed (inferredbugs-csharp: TrueCraft/4)
--FILE--
<?php
$clients = [];
$worlds = [];
$entityManagers = [];
$logProviders = [];
$packetHandlers = array_fill(0, 256, null);

$world = (object)['id' => 1];
$clients[] = (object)[
    'loggedIn' => true,
    'world' => $world,
    'username' => 'alice',
    'packetQueue' => new SplQueue(),
    'dataAvailable' => false,
    'minecraftStream' => fopen('php://memory', 'r+')
];
$clients[0]->packetQueue->enqueue((object)['id' => 1, 'type' => 'DisconnectPacket']);
$clients[0]->packetQueue->enqueue((object)['id' => 2, 'type' => 'OtherPacket']);

$packetHandlers[2] = function ($packet, $client, &$server) {
    $client->handled = true;
};

for ($i = 0; $i < count($clients); $i++) {
    $client = $clients[$i];
    if (!$client->packetQueue->isEmpty()) {
        while (!$client->packetQueue->isEmpty()) {
            $packet = $client->packetQueue->dequeue();
        }
        if ($packet->type === 'DisconnectPacket') {
            if ($client->loggedIn) {
                foreach ($logProviders as $provider) {
                    $provider('Notice', $client->username . ' has left the server.');
                }
            }
            array_splice($clients, $i, 1);
            $i--;
            continue;
        }
    }
    if ($client->dataAvailable) {
        $packet = (object)['id' => 2, 'type' => 'OtherPacket'];
        if ($packetHandlers[$packet->id] !== null) {
            try {
                $packetHandlers[$packet->id]($packet, $client, $clients);
            } catch (Throwable $e) {
                array_splice($clients, $i, 1);
                $i--;
            }
        }
    }
}

$blockChange = (object)['position' => (object)['x' => 1, 'y' => 2, 'z' => 3], 'newBlock' => (object)['id' => 4, 'metadata' => 5]];
foreach ($clients as $client) {
    if ($client->loggedIn && $client->world === $world) {
        $client->queuedBlockChange = [$blockChange->position->x, (int)$blockChange->position->y, $blockChange->position->z, (int)$blockChange->newBlock->id, (int)$blockChange->newBlock->metadata];
    }
}
?>
--EXPECT--
