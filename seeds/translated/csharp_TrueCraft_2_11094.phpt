--TEST--
Translated seed (inferredbugs-csharp: TrueCraft/2)
--FILE--
<?php
$clients = [];
$packetHandlers = array_fill(0, 256, null);

$packetHandlers[1] = function (&$packet, &$client, &$server) {
    if ($packet['id'] === 1) {
        $client['queue'][] = ['id' => 2];
    }
};

$packetHandlers[2] = function (&$packet, &$client, &$server) {
    throw new Exception('boom');
};

$clients[] = ['loggedIn' => true, 'queue' => [['id' => 1], ['id' => 2]], 'dataAvailable' => true];
$clients[] = ['loggedIn' => false, 'queue' => [], 'dataAvailable' => true];

for ($i = 0; $i < count($clients); $i++) {
    $client =& $clients[$i];
    if (!empty($client['queue'])) {
        while (($packet = array_shift($client['queue'])) !== null) {
        }
        if ($packet['id'] === 2) {
            if ($client['loggedIn']) {
                $clients = array_values(array_filter($clients, fn($c) => $c !== $client));
                $i--;
            }
        }
    }
    if (!empty($client['dataAvailable'])) {
        $packet = ['id' => $i === 0 ? 1 : 2];
        $handler = $packetHandlers[$packet['id']];
        if ($handler !== null) {
            try {
                $handler($packet, $client, $clients);
            } catch (Throwable $e) {
                if ($client['loggedIn']) {
                    $clients = array_values(array_filter($clients, fn($c) => $c !== $client));
                    $i--;
                }
            }
        }
    }
}

echo count($clients), PHP_EOL;
--EXPECT--
