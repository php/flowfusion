--TEST--
Translated seed (inferredbugs-csharp: openssl-net/1)
--FILE--
<?php
$serverCertificate = ['has_private_key' => true, 'private_key' => 'k'];
$clientCertificateRequired = true;
$caCerts = [
    ['subject' => 'CN=one'],
    ['subject' => 'CN=two'],
];
$enabledSslProtocols = 0;
$sslStrength = 0;
$checkCertificateRevocation = false;

if ($serverCertificate === null) {
    throw new InvalidArgumentException('Server certificate cannot be null');
}
if (empty($serverCertificate['has_private_key'])) {
    throw new InvalidArgumentException('Server certificate must have a private key');
}

$context = stream_context_create([
    'ssl' => [
        'verify_peer' => $clientCertificateRequired,
        'verify_peer_name' => false,
        'allow_self_signed' => !$clientCertificateRequired,
        'capture_peer_cert' => true,
        'capture_peer_cert_chain' => true,
        'SNI_enabled' => true,
        'peer_name' => 'localhost',
        'ciphers' => 'DEFAULT',
        'crypto_method' => STREAM_CRYPTO_METHOD_TLS_SERVER,
    ],
]);

$sessionIdContext = substr(hash('sha256', basename(__FILE__)), 0, 16);
$caList = [];
foreach ($caCerts as $cert) {
    $caList[] = $cert['subject'];
}

$socket = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
if ($socket === false) {
    exit(0);
}
stream_set_blocking($socket[0], false);
stream_set_blocking($socket[1], false);

fwrite($socket[0], "CLIENTHELLO");
$buf = fread($socket[1], 8192);
if ($buf === false) {
    $buf = '';
}

$pending = strlen($buf);
if ($pending > 0) {
    fwrite($socket[1], "ALERT");
}

fclose($socket[0]);
fclose($socket[1]);
?>
--EXPECT--
