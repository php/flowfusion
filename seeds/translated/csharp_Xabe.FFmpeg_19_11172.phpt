--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/19)
--FILE--
<?php
$path = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($path, "x");
$u = parse_url("http://example.com/a");
if (($u['scheme'] ?? '') !== 'http' || ($u['scheme'] ?? '') !== 'https') {
    $ok = true;
}
$size = ['width' => 0, 'height' => 0];
$source = ['width' => 1920, 'height' => 1080];
if (($size['height'] ?? null) === null || (($size['height'] ?? 0) == 0 && ($size['width'] ?? 0) == 0)) {
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
@unlink($path);
echo "done\n";
?>
--EXPECT--
