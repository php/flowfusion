--TEST--
Translated seed (inferredbugs-csharp: VirusTotalNet/3)
--FILE--
<?php
$u = "http://example.com";
$p = parse_url($u);
if ($p === false || !isset($p["scheme"], $p["host"])) {
    $u = "http://" . trim($u);
    $p = parse_url($u);
}
if ($p === false) {
    exit(0);
}
$host = $p["host"];
$scheme = strtolower($p["scheme"]) === "https" ? "https" : "http";
$base = $scheme . "://" . $host;
$paths = [
    "/vtapi/v2/file/scan",
    "/vtapi/v2/file/report",
    "/vtapi/v2/url/scan",
    "/vtapi/v2/url/report",
    "/vtapi/v2/ip-address/report",
    "/vtapi/v2/domain/report",
    "/vtapi/v2/comments/put"
];
foreach ($paths as $path) {
    $x = $base . $path;
    parse_url($x);
}
$vals = [
    "",
    " ",
    str_repeat("a", 31),
    str_repeat("b", 32),
    str_repeat("c", 40),
    str_repeat("d", 64),
    str_repeat("e", 75),
    str_repeat("f", 76)
];
foreach ($vals as $v) {
    $len = strlen(trim($v));
    if ($len !== 32 && $len !== 40 && $len !== 64 && $len !== 75) {
        continue;
    }
    hash("sha256", $v, false);
}
$urls = [
    "example.com",
    "https://example.com",
    "http://example.com/a?b=c",
    "   example.org/path   "
];
foreach ($urls as $url) {
    $t = trim($url);
    if ($t === "") {
        continue;
    }
    if (stripos($t, "http://") !== 0 && stripos($t, "https://") !== 0) {
        $t = "http://" . $t;
    }
    parse_url($t);
}
$stream = fopen("php://temp", "r+");
fwrite($stream, str_repeat("x", 3));
rewind($stream);
stream_get_contents($stream);
fclose($stream);
echo "";
?>
--EXPECT--
