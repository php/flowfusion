--TEST--
Translated seed (inferredbugs-csharp: Smidge/1)
--FILE--
<?php
$headers = [
    'User-Agent' => 'Mozilla/5.0',
    'Accept-Encoding' => 'br, gzip;q=1.0, deflate;q=0.5',
];

$type = 'none';
$agentHeader = $headers['User-Agent'] ?? null;
if ($agentHeader !== null && strpos($agentHeader, 'MSIE 6') !== false) {
    echo $type;
    exit;
}

$acceptEncoding = $headers['Accept-Encoding'] ?? null;
if ($acceptEncoding !== null && $acceptEncoding !== '') {
    $supported = explode(',', $acceptEncoding);
    for ($i = 0; $i < count($supported); $i++) {
        if (strpos($supported[$i], 'deflate') !== false) {
            $type = 'deflate';
            break;
        } elseif (strpos($supported[$i], 'gzip') !== false) {
            $type = 'gzip';
            break;
        }
    }
}

echo $type;
--EXPECT--
