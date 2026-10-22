--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/4)
--FILE--
<?php
$src = "a\r\nb\nc\r\nd";
$norm = preg_split("/\r\n|\r|\n/", $src);
$joined = implode("\n", $norm);
$parts = explode("\n", $joined);
$map = [];
foreach ($parts as $i => $v) {
    $map[$i] = trim($v);
}
$uri = "ftps://user:pass@example.com:2121/path/to/file";
$p = parse_url($uri);
$scheme = strtolower($p["scheme"] ?? "");
if ($scheme !== "ftp" && $scheme !== "ftps") {
    $scheme = "ftp";
}
$host = $p["host"] ?? "";
$port = $p["port"] ?? 21;
$user = $p["user"] ?? "";
$pass = $p["pass"] ?? "";
$path = $p["path"] ?? "/";
$cwd = dirname($path);
if ($cwd === "\\" || $cwd === ".") $cwd = "/";
$sizeText = "  type=file;Size=9223372036854775807;modify=20240101123456; file.txt";
preg_match('/Size=(\d+);/i', $sizeText, $m);
$size = isset($m[1]) ? (int)$m[1] : 0;
$mdtm = "20240101123456.789";
$dt = DateTime::createFromFormat("YmdHis.u", str_pad($mdtm, 17, "0"));
if (!$dt) $dt = DateTime::createFromFormat("YmdHis", substr($mdtm, 0, 14));
$buf = str_repeat("x", 8192);
$stream = fopen("php://temp", "w+b");
fwrite($stream, $buf);
fseek($stream, 4096);
fwrite($stream, "REST");
rewind($stream);
while (!feof($stream)) {
    fread($stream, 1024);
}
fclose($stream);
echo "";
?>
--EXPECT--
