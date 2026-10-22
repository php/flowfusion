--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/9)
--FILE--
<?php
$u = "magnet:?xt=urn:btih:e4be9e4db876e3e3179778b03e906297be5c8dbe&dn=ubuntu-18.04-desktop-amd64.iso&tr=http://torrent.ubuntu.com:6969/announce";
$p = parse_url($u);
parse_str($p["query"] ?? "", $q);
$tr = $q["tr"] ?? "";
$xt = $q["xt"] ?? "";
$ih = str_starts_with($xt, "urn:btih:") ? substr($xt, 9) : "";
$dn = $q["dn"] ?? "";
$parts = [];
parse_str(str_replace("&", "&", $p["query"] ?? ""), $parts);
$announce = [];
foreach (["tr"] as $k) {
    if (isset($parts[$k])) $announce[] = $parts[$k];
}
$u2 = "magnet:?xt=urn:btih:e4be9e4db876e3e3179778b03e906297be5c8dbe&dn=ubuntu-18.04-desktop-amd64.iso&tr=udp://tracker.opentrackr.org:1337/announce";
$p2 = parse_url($u2);
parse_str($p2["query"] ?? "", $q2);
$announce2 = [];
if (isset($q2["tr"])) $announce2[] = $q2["tr"];
$infohash = hex2bin("e4be9e4db876e3e3179778b03e906297be5c8dbe");
$peer_id = "-LW2222-011345223110";
$client_id = "-ST2222-011345223110";
$payload = pack("N", 0) . $infohash . $peer_id;
$h = hash("sha1", $payload, true);
$hex = bin2hex($h);
$pieces = str_split($hex, 8);
$map = [];
foreach ($pieces as $i => $piece) $map[$i] = $piece;
$buf = "";
foreach ($announce2 as $a) $buf .= $a . "\n";
for ($i = 0; $i < 2; $i++) {
    $buf .= "udp://tracker.opentrackr.org:1337/announce\n";
}
$scrape = ["e4be9e4db876e3e3179778b03e906297be5c8dbe"];
foreach ($scrape as $s) {
    $buf .= $s . "\n";
}
$state = 0;
for ($i = 0; $i < 3; $i++) {
    $state ^= crc32($buf . $i . $client_id);
}
echo "";
?>
--EXPECT--
