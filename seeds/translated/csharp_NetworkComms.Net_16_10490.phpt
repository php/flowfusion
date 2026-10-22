--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/16)
--FILE--
<?php
$seq = 0;
$sent = [];
$callbacks = [];
$shutdown = [];
$alive = true;
$connectionState = 'Established';
$applicationLayerProtocol = true;
$receiveConfirmationRequired = true;
$packetType = 'Data';
$payload = random_bytes(16);
$header = ['type' => $packetType, 'seq' => null, 'confirm' => $receiveConfirmationRequired, 'hash' => md5($payload)];
$callbacks['Confirmation'] = function ($seqNo) use (&$callbacks, &$alive) {
    if ($seqNo >= 0) $alive = $alive && true;
};
$shutdown[] = function () use (&$alive) {
    $alive = false;
};
if ($applicationLayerProtocol && $connectionState === 'Established') {
    $header['seq'] = $seq++;
    if ($receiveConfirmationRequired) {
        $callbacks['Confirmation']($header['seq']);
    }
    $sent[$header['hash']] = ['time' => microtime(true), 'data' => $payload];
    if (count($sent) > 1) {
        $cutoff = microtime(true) - 60;
        foreach ($sent as $k => $v) {
            if ($v['time'] < $cutoff) unset($sent[$k]);
        }
    }
}
if ($connectionState === 'Shutdown') {
    foreach ($shutdown as $cb) $cb();
}
echo "";
?>
--EXPECT--
