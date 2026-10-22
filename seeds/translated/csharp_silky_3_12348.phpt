--TEST--
Translated seed (inferredbugs-csharp: silky/3)
--FILE--
<?php

$attachments = [
    'SelectedServerEndpoint' => 'skip1',
    'SelectedServerHost' => 'skip2',
    'SelectedServerPort' => 'skip3',
    'SelectedServerServiceProtocol' => 'skip4',
    'user' => 'alice',
    'trace' => 't-123',
    'empty' => '',
    'zero' => 0,
];

$context = [];

foreach ($attachments as $k => $v) {
    if ($k === 'SelectedServerEndpoint' || $k === 'SelectedServerHost' || $k === 'SelectedServerPort' || $k === 'SelectedServerServiceProtocol') {
        continue;
    }
    $context[$k] = $v;
}

$localRpcEndpoint = (object)[
    'Host' => '127.0.0.1',
    'Port' => 8080,
    'ServiceProtocol' => 'http',
];

$isGateway = false;
$context['LocalAddress'] = $localRpcEndpoint->Host;
$context['LocalPort'] = $localRpcEndpoint->Port;
$context['LocalServiceProtocol'] = $localRpcEndpoint->ServiceProtocol;
$context['IsGateway'] = $isGateway;

echo "";
?>
--EXPECT--
