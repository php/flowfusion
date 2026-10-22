--TEST--
Translated seed (inferredbugs-csharp: SteamKit/1)
--FILE--
<?php
$start = microtime(true);
$connected = false;
$hasSessionToken = false;
$appTicket = null;
$sessionToken = 0;
$depotId = 12345;
$queue = [
    ['type' => 'connect'],
    ['type' => 'login', 'result' => 'OK'],
    ['type' => 'ticket', 'appId' => $depotId, 'result' => 'OK', 'ticket' => random_bytes(8)],
    ['type' => 'token', 'sessionToken' => random_int(1, PHP_INT_MAX)],
];

while (true) {
    $callback = array_shift($queue);
    if ((microtime(true) - $start) > 30 && !$connected) {
        break;
    }
    if ($hasSessionToken && $appTicket !== null) {
        break;
    }
    if ($callback === null) {
        continue;
    }
    if ($callback['type'] === 'connect') {
        $connected = true;
    }
    if ($callback['type'] === 'login') {
        if ($callback['result'] !== 'OK') {
            break;
        }
    }
    if ($callback['type'] === 'ticket') {
        if ($callback['appId'] !== $depotId) {
            continue;
        }
        if ($callback['result'] !== 'OK') {
            break;
        }
        $appTicket = $callback['ticket'];
    }
    if ($callback['type'] === 'token') {
        $sessionToken = $callback['sessionToken'];
        $hasSessionToken = true;
    }
}
echo "";
?>
--EXPECT--
