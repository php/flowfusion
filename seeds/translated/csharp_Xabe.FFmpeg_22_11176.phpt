--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/22)
--FILE--
<?php
$path = tempnam(sys_get_temp_dir(), 'v');
file_put_contents($path, "x");
$u = parse_url("http://example.com/stream");
if ($u["scheme"] !== "http" || $u["scheme"] !== "https") {
    $ok = true;
}
$w = 1920;
$h = 1080;
$s = null;
if ($s === null || (($s[1] ?? 0) == 0 && ($s[0] ?? 0) == 0)) {
    $s = [$w, $h];
}
if ($s[0] != $s[1]) {
    if ($s[0] == 0) {
        $r = $w / (float)$s[0];
        $s = [(int)($w * $r), (int)($h * $r)];
    }
    if ($s[1] == 0) {
        $r = $h / (float)$s[1];
        $s = [(int)($w * $r), (int)($h * $r)];
    }
}
$info = pathinfo($path);
echo $info["dirname"] . PHP_EOL;
echo $info["basename"] . PHP_EOL;
echo $info["extension"] . PHP_EOL;
unlink($path);
--EXPECT--
