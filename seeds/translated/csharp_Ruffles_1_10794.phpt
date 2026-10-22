--TEST--
Translated seed (inferredbugs-csharp: Ruffles/1)
--FILE--
<?php
$messages = [random_bytes(1024), random_bytes(1024), random_bytes(1024)];

$pack = function (string $msg, int $seq): string {
    return pack('nN', $seq, strlen($msg)) . $msg;
};

$buf1 = $pack($messages[0], 0);
$buf2 = $pack($messages[1], 1);
$buf3 = $pack($messages[2], 2);

$inOrder = function (string $packet): string {
    $len = unpack('N', substr($packet, 2, 4))[1];
    return substr($packet, 6, $len);
};

$reorderBuffer = [];
$expected = 0;
$deliver = [];

$handle = function (string $packet) use (&$reorderBuffer, &$expected, &$deliver): ?string {
    $seq = unpack('n', substr($packet, 0, 2))[1];
    $len = unpack('N', substr($packet, 2, 4))[1];
    $payload = substr($packet, 6, $len);
    if ($seq === $expected) {
        $deliver[] = $payload;
        $expected++;
        while (isset($reorderBuffer[$expected])) {
            $deliver[] = $reorderBuffer[$expected];
            unset($reorderBuffer[$expected]);
            $expected++;
        }
        return $payload;
    }
    if ($seq > $expected) {
        $reorderBuffer[$seq] = $payload;
    }
    return null;
};

$p1 = $handle($buf1);
$p3 = $handle($buf3);
$p2 = $handle($buf2);

$poll = null;
if (isset($reorderBuffer[$expected])) {
    $poll = $reorderBuffer[$expected];
    unset($reorderBuffer[$expected]);
    $expected++;
}

if ($p1 !== $messages[0]) {
    exit(1);
}
if ($p3 !== null) {
    exit(1);
}
if ($p2 !== $messages[1]) {
    exit(1);
}
if ($poll !== $messages[2]) {
    exit(1);
}
?>
--EXPECT--
