--TEST--
Translated seed (inferredbugs-csharp: sipsorcery/2)
--FILE--
<?php
declare(strict_types=1);

$ip = '127.0.0.1';
$port = 5060;
$endpoint = $ip . ':' . $port;

$buffer = "INVITE sip:user@" . $endpoint . " SIP/2.0\r\nVia: SIP/2.0/UDP " . $endpoint . "\r\n\r\n";
$lastSent = $buffer;

$parsed = filter_var($ip, FILTER_VALIDATE_IP) ? $endpoint : null;

$sdp = [
    'sessionId' => (string) random_int(0, 31),
    'connection' => $ip,
    'media' => [
        [
            'type' => 'audio',
            'port' => 1234,
            'transport' => 'RTP/AVP',
            'formats' => ['PCMU'],
        ],
    ],
];

$remoteDescription = $sdp;
$isClosed = false;
$isClosed = true;

echo $lastSent, PHP_EOL;
echo ($parsed ?? 'null'), PHP_EOL;
echo $remoteDescription['sessionId'], PHP_EOL;
echo ($isClosed ? 'closed' : 'open'), PHP_EOL;
?>
--EXPECT--
