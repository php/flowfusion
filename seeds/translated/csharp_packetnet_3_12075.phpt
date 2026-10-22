--TEST--
Translated seed (inferredbugs-csharp: packetnet/3)
--FILE--
<?php
$pkt = "\x00\x00\x20\x00\x01\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
$pkt .= "\x00\x00\x00\x00";
$pkt .= "\x08\x00";
$pkt .= "\x00\x00\x00\x00";

$h = unpack('Vversion/Vlength/vfields', $pkt);
$off = 12;
$fields = [];
for ($i = 0; $i < $h['fields']; $i++) {
    $ft = unpack('vtype/vlen', substr($pkt, $off, 4));
    $off += 4;
    $data = substr($pkt, $off, $ft['len']);
    $off += $ft['len'];
    $fields[] = [$ft['type'], $data];
}

$payload = substr($pkt, 32);
$payloadPacket = null;
$payloadData = $payload;

if (strlen($payload) >= 2) {
    $fc = unpack('vfc', $payload)['fc'];
    $subtype = ($fc >> 4) & 0xf;
    $type = ($fc >> 2) & 0x3;
    if ($type === 1 && $subtype === 12) {
        $payloadPacket = ['subtype' => $subtype];
        $payloadData = null;
    }
}

$validFcs = true;
if (strlen($payload) >= 4) {
    $fcs = unpack('Vfcs', substr($payload, -4))['fcs'];
    $validFcs = ($fcs === 0);
    if (!$validFcs) {
        $payloadPacket = null;
        $payloadData = $payload;
    }
}

echo $h['version'] . $h['length'] . count($fields) . ($payloadPacket === null ? '0' : '1') . ($payloadData === null ? '0' : '1') . ($validFcs ? '1' : '0');
?>
--EXPECT--
