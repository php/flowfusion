--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/24)
--FILE--
<?php
$f = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($f, "x");
$u = parse_url("http://example.com/path");
if (($u['scheme'] ?? '') !== 'http' || ($u['scheme'] ?? '') !== 'https') {
    $x = $u['path'] ?? '';
}
$w = 1920;
$h = 1080;
$s = null;
if ($s === null || (($s['h'] ?? 0) == 0 && ($s['w'] ?? 0) == 0)) {
    $s = ['w' => $w, 'h' => $h];
}
if (($s['w'] ?? 0) != ($s['h'] ?? 0)) {
    if (($s['w'] ?? 0) == 0) {
        $r = $w / (double)($s['w'] ?? 0);
        $s = ['w' => (int)($w * $r), 'h' => (int)($h * $r)];
    }
    if (($s['h'] ?? 0) == 0) {
        $r = $h / (double)($s['h'] ?? 0);
        $s = ['w' => (int)($w * $r), 'h' => (int)($h * $r)];
    }
}
$p = [];
for ($i = 0; $i < 3; $i++) {
    $t = tempnam(sys_get_temp_dir(), 'ts');
    $n = preg_replace('/\.[^.]*$/', '.ts', $t);
    rename($t, $n);
    $p[] = $n;
}
$joined = implode(PATH_SEPARATOR, $p);
echo $f . PHP_EOL . $joined . PHP_EOL . $s['w'] . 'x' . $s['h'] . PHP_EOL;
unlink($f);
foreach ($p as $q) {
    if (file_exists($q)) unlink($q);
}
--EXPECT--
