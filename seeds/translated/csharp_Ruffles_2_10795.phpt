--TEST--
Translated seed (inferredbugs-csharp: Ruffles/2)
--FILE--
<?php
mt_srand(1);
$seq = 0;
$pending = [];
$deliver = function(array $packet) use (&$seq, &$pending) {
    $id = ($packet[0] << 8) | $packet[1];
    $payload = substr($packet, 2);
    if ($id === $seq) {
        $seq = ($seq + 1) & 0xffff;
        while (isset($pending[$seq])) {
            $payload = $pending[$seq];
            unset($pending[$seq]);
            $seq = ($seq + 1) & 0xffff;
        }
        return $payload;
    }
    $pending[$id] = $payload;
    return null;
};

$m1 = random_bytes(1024);
$m2 = random_bytes(1024);
$m3 = random_bytes(1024);

$p1 = pack('n', 0) . $m1;
$p2 = pack('n', 1) . $m2;
$p3 = pack('n', 2) . $m3;

$r1 = $deliver(str_split($p1, 1));
$r3 = $deliver(str_split($p3, 1));
$r2 = $deliver(str_split($p2, 1));

if ($r1 !== $m1) exit(1);
if ($r3 !== null) exit(1);
if ($r2 !== $m2) exit(1);

$flush = $deliver([0, 0]);
if ($flush !== null) exit(1);
?>
--EXPECT--
