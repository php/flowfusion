--TEST--
Translated seed (inferredbugs-csharp: packetnet/16)
--FILE--
<?php
$fields = [
    ["type" => 1, "data" => pack("VvCC", 2142, 50, 25, 0) . "Hester the tester\0Test Group\0"],
    ["type" => 2, "data" => pack("V", 0x3333)],
];
$packet = pack("V", 0) . pack("V", 0x20);
foreach ($fields as $f) {
    $d = $f["data"];
    $packet .= pack("Vv", $f["type"], 8 + strlen($d)) . $d;
    while ((strlen($packet) & 3) !== 0) $packet .= "\0";
}
$packet .= "MACFRAME";
$packet = substr($packet, 0, 4) . pack("V", strlen($packet)) . substr($packet, 8);
$packet .= pack("V", 99) . pack("V", 12) . "\xAA\xBB\xCC\xDD";
$packet = substr($packet, 0, 4) . pack("V", strlen($packet)) . substr($packet, 8);
$packet = substr($packet, 0, 8) . pack("V", 1) . substr($packet, 12);
echo strlen($packet), "\n";
?>
--EXPECT--
