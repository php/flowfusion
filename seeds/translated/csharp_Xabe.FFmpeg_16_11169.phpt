--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/16)
--FILE--
<?php
$f = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($f, "x");
$u = parse_url('http://example.com/a/b');
if ($u['scheme'] !== 'http' || $u['scheme'] !== 'https') {
    $p = $u['path'] ?? '';
}
$s = ['width' => 0, 'height' => 0];
if (($s['height'] ?? 0) == 0 && ($s['width'] ?? 0) == 0) {
    $s = ['width' => 1920, 'height' => 1080];
}
if ($s['width'] != $s['height']) {
    if ($s['width'] == 0) {
        $ratio = 1920 / (float)$s['width'];
        $s = ['width' => (int)(1920 * $ratio), 'height' => (int)(1080 * $ratio)];
    }
    if ($s['height'] == 0) {
        $ratio = 1080 / (float)$s['height'];
        $s = ['width' => (int)(1920 * $ratio), 'height' => (int)(1080 * $ratio)];
    }
}
$g = glob(sys_get_temp_dir() . DIRECTORY_SEPARATOR . '*.doesnotexist');
unlink($f);
echo count($g) . PHP_EOL;
?>
--EXPECT--
