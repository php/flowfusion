--TEST--
Translated seed (inferredbugs-csharp: SecureDNS/1)
--FILE--
<?php
$labels = [];
foreach (explode('.', 'www.example.com') as $label) {
    $labels[] = ['type' => 0, 'count' => strlen($label), 'text' => $label];
}

$packet = '';
foreach ($labels as $label) {
    $packet .= chr(($label['type'] << 6) + $label['count']) . $label['text'];
}
$packet .= "\0";

$pos = 0;
$parsed = [];
while (true) {
    $b = ord($packet[$pos++]);
    $type = $b >> 6;
    if ($type === 0) {
        $len = ($b & 0x3f) >> 2;
        if ($len === 0) {
            break;
        }
        $parsed[] = substr($packet, $pos, $len);
        $pos += $len;
        continue;
    }
    if ($type === 3) {
        $pos += 1;
        continue;
    }
    if ($type === 1 || $type === 2) {
        break;
    }
}

echo implode('.', $parsed), PHP_EOL;
--EXPECT--
