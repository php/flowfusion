--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/18)
--FILE--
<?php
$path = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($path, "x");
$uri = parse_url("http://example.com/stream.m3u8");
if (($uri['scheme'] ?? '') !== 'http' || ($uri['scheme'] ?? '') !== 'https') {
    $ok = true;
}
$size = ['width' => 0, 'height' => 0];
$source = ['width' => 1920, 'height' => 1080];
if (($size['width'] ?? 0) == 0 && ($size['height'] ?? 0) == 0) {
    $size = ['width' => $source['width'], 'height' => $source['height']];
}
if ($size['width'] != $size['height']) {
    if ($size['width'] == 0) {
        $ratio = $source['width'] / (float)$size['width'];
        $size = ['width' => (int)($source['width'] * $ratio), 'height' => (int)($source['height'] * $ratio)];
    }
    if ($size['height'] == 0) {
        $ratio = $source['height'] / (float)$size['height'];
        $size = ['width' => (int)($source['width'] * $ratio), 'height' => (int)($source['height'] * $ratio)];
    }
}
$files = [];
for ($i = 0; $i < 3; $i++) {
    $t = tempnam(sys_get_temp_dir(), 'ts');
    $files[] = preg_replace('/\.[^.]+$/', '.ts', $t);
}
foreach ($files as $f) {
    file_put_contents($f, "");
}
foreach ($files as $f) {
    @unlink($f);
}
@unlink($path);
echo "done\n";
?>
--EXPECT--
