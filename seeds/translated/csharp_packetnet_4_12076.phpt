--TEST--
Translated seed (inferredbugs-csharp: packetnet/4)
--FILE--
<?php
$files = [
    '80211_plus_radiotap_header.pcap',
    '80211_radio_without_fcs.pcap',
];

foreach ($files as $file) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
    if (!is_file($path)) {
        $path = $file;
    }
    $data = @file_get_contents($path);
    if ($data === false || strlen($data) < 24) {
        continue;
    }

    $le = substr($data, 0, 4) === "\xd4\xc3\xb2\xa1";
    $u32 = function (string $s) use ($le): int {
        $v = unpack($le ? 'V' : 'N', $s);
        return $v[1];
    };

    $off = 24;
    while ($off + 16 <= strlen($data)) {
        $incl = $u32(substr($data, $off + 8, 4));
        $off += 16;
        if ($off + $incl > strlen($data)) {
            break;
        }
        $pkt = substr($data, $off, $incl);
        $off += $incl;

        if ($file === '80211_plus_radiotap_header.pcap') {
            if (strlen($pkt) >= 8) {
                $rt_len = unpack($le ? 'v' : 'n', substr($pkt, 2, 2))[1];
                $payload = substr($pkt, $rt_len);
                if ($payload !== '') {
                    echo strlen($payload), PHP_EOL;
                }
            }
        } else {
            if (strlen($pkt) >= 4) {
                $fc = unpack('v', substr($pkt, 0, 2))[1];
                $type = ($fc >> 2) & 3;
                $subtype = ($fc >> 4) & 15;
                $has_fcs = (strlen($pkt) >= 4) && (substr($pkt, -4) !== "\0\0\0\0");
                echo $type, ':', $subtype, ':', ($has_fcs ? 1 : 0), PHP_EOL;
            }
        }
        break;
    }
}
?>
--EXPECT--
