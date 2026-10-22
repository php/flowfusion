--TEST--
Translated seed (inferredbugs-csharp: packetnet/20)
--FILE--
<?php
$frameControl = 0;
$frameControl |= 0 << 0;
$frameControl |= 8 << 4;
$frameControl |= 1 << 9;
$frameControl |= 1 << 14;
$duration = 44;
$dst = hex2bin('7CC5376D16E7');
$bssid = hex2bin('0024B2F8D706');
$src = hex2bin('0024B2F8D706');
$seq = (2 << 4) | 0;
$qos = 0x00;
$fcs = 0x87311A87;
$payload = str_repeat("\x00", 0x34);

$bytes = pack('v', $frameControl)
    . pack('v', $duration)
    . $dst
    . $src
    . $bssid
    . pack('v', $seq)
    . pack('v', $qos)
    . $payload
    . pack('V', $fcs);

$u = unpack('vfc/vdur/a6dst/a6src/a6bssid/vseq/vqos/a*payload/Vfcs', $bytes);
$fc = $u['fc'];
$dur = $u['dur'];
$seq = $u['seq'];

$protocolVersion = $fc & 0x3;
$subType = ($fc >> 4) & 0xF;
$toDS = (bool)(($fc >> 8) & 1);
$fromDS = (bool)(($fc >> 9) & 1);
$moreFragments = (bool)(($fc >> 10) & 1);
$retry = (bool)(($fc >> 11) & 1);
$powerManagement = (bool)(($fc >> 12) & 1);
$moreData = (bool)(($fc >> 13) & 1);
$protected = (bool)(($fc >> 14) & 1);
$order = (bool)(($fc >> 15) & 1);
$fragmentNumber = $seq & 0xF;
$sequenceNumber = ($seq >> 4) & 0xFFF;

$repacked = pack('v', $fc)
    . pack('v', $dur)
    . $u['dst']
    . $u['src']
    . $u['bssid']
    . pack('v', $seq)
    . pack('v', $u['qos'])
    . $u['payload']
    . pack('V', $u['fcs']);

$short = "\x01";
$ok = strlen($short) < 24;

$tcp = substr($bytes, 0, 0);
echo $protocolVersion . $subType . (int)$toDS . (int)$fromDS . (int)$moreFragments . (int)$retry . (int)$powerManagement . (int)$moreData . (int)$protected . (int)$order . $dur . bin2hex($u['dst']) . bin2hex($u['bssid']) . bin2hex($u['src']) . $fragmentNumber . $sequenceNumber . $u['qos'] . $u['fcs'] . strlen($u['payload']) . strlen($repacked) . (int)$ok . strlen($tcp);
?>
--EXPECT--
