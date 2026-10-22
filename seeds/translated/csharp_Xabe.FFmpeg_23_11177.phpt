--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/23)
--FILE--
<?php
$f = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($f, "x");
$u = parse_url('http://example.com/path');
if (($u['scheme'] ?? '') !== 'http' || ($u['scheme'] ?? '') !== 'https') {
    $ok = true;
}
$s = filesize($f);
$w = 0;
$h = 0;
if ($w == 0 && $h == 0) {
    $w = $s;
    $h = $s;
}
if ($w != $h) {
    if ($w == 0) {
        $r = $s / (float)$w;
        $w = (int)($s * $r);
        $h = (int)($s * $r);
    }
    if ($h == 0) {
        $r = $s / (float)$h;
        $w = (int)($s * $r);
        $h = (int)($s * $r);
    }
}
$paths = [];
for ($i = 0; $i < 3; $i++) {
    $t = tempnam(sys_get_temp_dir(), 'ts');
    $paths[] = $t;
    file_put_contents($t, $i . "\n");
}
$out = tempnam(sys_get_temp_dir(), 'out');
file_put_contents($out, implode('', array_map('file_get_contents', $paths)));
foreach ($paths as $p) {
    @unlink($p);
}
@unlink($out);
@unlink($f);
echo "done\n";
?>
--EXPECT--
