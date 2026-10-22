--TEST--
Translated seed (inferredbugs-csharp: TrueCraft/1)
--FILE--
<?php
$clients = [];
$packetHandlers = array_fill(0, 256, null);

$clients[] = (object)[
    'PacketQueue' => new SplQueue(),
    'DataAvailable' => false,
    'MinecraftStream' => fopen('php://memory', 'r+'),
];

$packetHandlers[0] = function ($packet, &$client, &$clients) {
    throw new Exception();
};

$clients[0]->PacketQueue->enqueue((object)['ID' => 0, 'name' => 'Ping']);
$clients[0]->DataAvailable = true;
fwrite($clients[0]->MinecraftStream, chr(0));
rewind($clients[0]->MinecraftStream);

for ($i = 0, $count = count($clients); $i < $count; $i++) {
    $client = $clients[$i];
    if ($client->PacketQueue->count() !== 0) {
        while ($client->PacketQueue->isEmpty()) {}
        $packet = $client->PacketQueue->dequeue();
        echo "Sending packet 0x" . strtoupper(str_pad(dechex($packet->ID), 2, '0', STR_PAD_LEFT)) . ": " . $packet->name . PHP_EOL;
    }
    if ($client->DataAvailable) {
        $id = ord(fread($client->MinecraftStream, 1));
        $packet = (object)['ID' => $id, 'name' => 'Ping'];
        if ($packetHandlers[$packet->ID] !== null) {
            echo "Got packet 0x" . strtoupper(str_pad(dechex($packet->ID), 2, '0', STR_PAD_LEFT)) . ": " . $packet->name . PHP_EOL;
            try {
                ($packetHandlers[$packet->ID])($packet, $client, $clients);
            } catch (Exception $e) {
                unset($clients[$i]);
                $i--;
                $count = count($clients);
            }
        } else {
            echo "WARNING: Got unhandled packet 0x" . strtoupper(str_pad(dechex($packet->ID), 2, '0', STR_PAD_LEFT)) . ": " . $packet->name . PHP_EOL;
        }
    }
}
?>
--EXPECT--
