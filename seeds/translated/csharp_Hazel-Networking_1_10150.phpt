--TEST--
Translated seed (inferredbugs-csharp: Hazel-Networking/1)
--FILE--
<?php
$maxDatagramSize = 1200;
$recordSize = 13;
$handshakeSize = 12;
$serverHelloSize = 38;
$finishedSize = 12;
$randomSize = 32;

$cookieKey = random_bytes(16);
$peer = [
    'epoch' => 0,
    'canApp' => false,
    'nextOut' => 2,
    'nextExpected' => 1,
    'prevMask' => 0,
    'queued' => [],
    'clientFinished' => str_repeat("\0", $finishedSize),
    'serverFinished' => str_repeat("\0", $finishedSize),
    'next' => [
        'state' => 0,
        'epoch' => 0,
        'nextOut' => 1,
        'clientRandom' => str_repeat("\0", $randomSize),
        'serverRandom' => str_repeat("\0", $randomSize),
        'clientVerify' => str_repeat("\0", $finishedSize),
        'serverVerify' => str_repeat("\0", $finishedSize),
        'verification' => '',
    ],
];

$cert = random_bytes(4096);
$encodedCertificates = [];
$padding = $recordSize + $handshakeSize + $serverHelloSize + $handshakeSize;
$off = 0;
$chunk = $maxDatagramSize - $padding;
$encodedCertificates[] = substr($cert, 0, $chunk);
$off += $chunk;
$padding = $recordSize + $handshakeSize;
while ($off < strlen($cert)) {
    $encodedCertificates[] = substr($cert, $off, $maxDatagramSize - $padding);
    $off += $maxDatagramSize - $padding;
}

$clientHello = random_bytes(128);
$peer['next']['verification'] .= $clientHello;
$peer['next']['state'] = 1;
$peer['next']['epoch'] = 1;
$peer['next']['clientRandom'] = random_bytes($randomSize);
$peer['next']['serverRandom'] = random_bytes($randomSize);

$flight = '';
$flight .= pack('C n n', 22, 0, 1) . str_repeat("\0", $handshakeSize + $serverHelloSize);
$flight .= pack('C n n', 22, 0, count($encodedCertificates)) . implode('', $encodedCertificates);
$flight .= pack('C n n', 22, 0, 1) . random_bytes(64);
$flight .= pack('C n n', 20, 0, 1) . "\1";
$flight .= pack('C n n', 22, 1, 1) . hash('sha256', $peer['next']['verification'], true);

echo strlen($flight) . "\n";
--EXPECT--
