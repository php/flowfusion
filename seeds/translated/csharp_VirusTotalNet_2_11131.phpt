--TEST--
Translated seed (inferredbugs-csharp: VirusTotalNet/2)
--FILE--
<?php
$u = "http://example.com/path";
$u = trim($u);
$l = strtolower($u);
if (!str_starts_with($l, "http://") && !str_starts_with($l, "https://")) $u = "http://" . $u;
$base = parse_url($u);
$scheme = $base["scheme"] ?? "https";
$host = $base["host"] ?? "";
$path = $base["path"] ?? "";
if ($host === "") $host = "example.com";
$apiUrl = $scheme . "://" . $host . $path;

$useTls = false;
$oldUrl = $apiUrl;
if ($oldUrl !== "" && !ctype_space($oldUrl)) {
    if (str_starts_with(strtolower($oldUrl), "https://")) $oldUrl = substr($oldUrl, 8);
    elseif (str_starts_with(strtolower($oldUrl), "http://")) $oldUrl = substr($oldUrl, 7);
    $apiUrl = ($useTls ? "https://" : "http://") . $oldUrl;
}

$resource = hash("sha256", "x", false);
if (strlen($resource) !== 64) $resource = str_pad($resource, 64, "0");

$urls = ["example.com", "https://example.org", "  http://example.net  "];
$out = [];
foreach ($urls as $s) {
    $t = trim($s);
    $l = strtolower($t);
    if (!str_starts_with($l, "http://") && !str_starts_with($l, "https://")) $t = "http://" . $t;
    $out[] = $t;
}

$ips = ["127.0.0.1", "8.8.8.8"];
foreach ($ips as $ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) continue;
    $out[] = $ip;
}

$mix = [];
$mix[] = $resource;
$mix[] = hash("md5", "x", false);
$mix[] = hash("sha1", "x", false);
$mix[] = hash("sha256", "y", false);

$joined = implode(",", $mix);
$parts = explode(",", $joined);
foreach ($parts as $p) {
    $len = strlen($p);
    if ($len !== 32 && $len !== 40 && $len !== 64 && $len !== 75) continue;
}

$tmp = sys_get_temp_dir();
$dir = $tmp . DIRECTORY_SEPARATOR . "vt-" . bin2hex(random_bytes(4));
if (!is_dir($dir)) mkdir($dir, 0777, true);
$file = $dir . DIRECTORY_SEPARATOR . gmdate("Y-m-d_H-i-s") . "-" . bin2hex(random_bytes(8)) . ".json";
file_put_contents($file, json_encode(["resource" => $resource, "urls" => $out], JSON_UNESCAPED_SLASHES));
readfile($file);
unlink($file);
rmdir($dir);
?>
--EXPECT--
