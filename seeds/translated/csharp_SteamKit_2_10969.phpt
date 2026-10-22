--TEST--
Translated seed (inferredbugs-csharp: SteamKit/2)
--FILE--
<?php
$queue = new SplQueue();
$connected = false;
$hasSessionToken = false;
$appTicket = null;
$connectTime = microtime(true);
$timeout = 30.0;

$queue->enqueue(['type' => 'connect']);
$queue->enqueue(['type' => 'login', 'result' => 'OK']);
$queue->enqueue(['type' => 'ticket', 'appId' => 1234, 'result' => 'OK', 'ticket' => random_bytes(8)]);
$queue->enqueue(['type' => 'session', 'token' => random_int(0, PHP_INT_MAX)]);

while (true) {
    $callback = $queue->isEmpty() ? null : $queue->dequeue();
    $diff = microtime(true) - $connectTime;

    if ($diff > $timeout && !$connected) break;
    if ($hasSessionToken && $appTicket !== null) break;
    if ($callback === null) continue;

    if ($callback['type'] === 'connect') {
        $connected = true;
    }

    if ($callback['type'] === 'login') {
        if ($callback['result'] !== 'OK') break;
    }

    if ($callback['type'] === 'ticket') {
        if ($callback['appId'] !== 1234) continue;
        if ($callback['result'] !== 'OK') break;
        $appTicket = $callback['ticket'];
    }

    if ($callback['type'] === 'session') {
        $hasSessionToken = true;
        $sessionToken = $callback['token'];
    }
}
echo "";
?>
--EXPECT--
