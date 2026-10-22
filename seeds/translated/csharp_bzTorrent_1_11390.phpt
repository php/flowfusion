--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/1)
--FILE--
<?php
$extensions = [];
$outgoing = [];
$incoming = [];

$extensions[] = ['protocol' => 'ut_metadata'];
$extensions[] = ['protocol' => 'ut_pex'];

$client = new stdClass();

$m = [];
$i = 1;
foreach ($extensions as $ext) {
    $outgoing[] = ['client' => $client, 'protocol' => $ext['protocol'], 'id' => $i];
    $m[$ext['protocol']] = $i;
    $i++;
}

$handshake = ['m' => $m];
$encoded = json_encode($handshake, JSON_UNESCAPED_SLASHES);
$bytes = $encoded === false ? '' : $encoded;
$length = 2 + strlen($bytes);

$message = pack('N', $length) . chr(20) . chr(0) . $bytes;

$payload = substr($message, 6);
$msgId = ord($payload[0]);
$buffer = substr($payload, 1, $length - 2);

if ($msgId === 0) {
    $decoded = json_decode($buffer, true);
    if (is_array($decoded) && isset($decoded['m']) && is_array($decoded['m'])) {
        foreach ($decoded['m'] as $protocol => $id) {
            $incoming[] = ['client' => $client, 'protocol' => $protocol, 'id' => (int)$id];
        }
    }
} else {
    foreach ($outgoing as $map) {
        if ($map['id'] === $msgId) {
            break;
        }
    }
}
?>
--EXPECT--
