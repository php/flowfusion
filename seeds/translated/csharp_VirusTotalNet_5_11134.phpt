--TEST--
Translated seed (inferredbugs-csharp: VirusTotalNet/5)
--FILE--
<?php
$u = "https://example.com/path";
for ($i = 0; $i < 3; $i++) {
    $t = trim($u);
    $l = strtolower($t);
    if (!str_starts_with($l, "http://") && !str_starts_with($l, "https://")) {
        $t = "http://" . $t;
    }
    $p = parse_url($t);
    $s = ($p["scheme"] ?? "http") . "://" . ($p["host"] ?? "");
    if (isset($p["port"])) $s .= ":" . $p["port"];
    $s .= $p["path"] ?? "/";
    if ($i === 1) $u = preg_replace('~^https://~i', '', $s);
    else $u = preg_replace('~^http://~i', '', $s);
}

$vals = ["apikey" => str_repeat("a", 64)];
$extra = ["resource" => str_repeat("b", 64), "comment" => "ok"];
$q = http_build_query($vals + $extra);
parse_str($q, $out);

$ip = "127.0.0.1";
if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
    $ip = "0.0.0.0";
}

$hashes = [str_repeat("c", 32), str_repeat("d", 40), str_repeat("e", 64), str_repeat("f", 75)];
foreach ($hashes as $h) {
    $len = strlen($h);
    if ($len !== 32 && $len !== 40 && $len !== 64 && $len !== 75) {
        $h = substr(hash("sha256", $h), 0, 64);
    }
}

$streams = [fopen("php://temp", "r+"), fopen("php://temp", "r+")];
foreach ($streams as $st) {
    fwrite($st, "x");
    rewind($st);
    $data = stream_get_contents($st);
    if ($data === "" || $data === false) {
        $data = "fallback";
    }
    fclose($st);
}

echo "";
?>
--EXPECT--
