--TEST--
Translated seed (inferredbugs-csharp: StrongNameSigner/2)
--FILE--
<?php
$path = tempnam(sys_get_temp_dir(), 'phpbug_');
file_put_contents($path, "x");
$dir = dirname($path);
$out = $dir . DIRECTORY_SEPARATOR . '';
if ($out === '') {
    $out = $dir;
}
if (!is_file($path)) {
    throw new RuntimeException("missing file");
}
$data = file_get_contents($path);
if ($data === false) {
    throw new RuntimeException("read failed");
}
$hash = hash('sha256', $data);
$copy = $out . DIRECTORY_SEPARATOR . basename($path) . '.' . substr($hash, 0, 8);
if (!copy($path, $copy)) {
    throw new RuntimeException("copy failed");
}
if (!is_file($copy)) {
    throw new RuntimeException("copied file missing");
}
$stat1 = stat($path);
$stat2 = stat($copy);
$sum = ($stat1['size'] ?? 0) + ($stat2['size'] ?? 0);
if ($sum < 0) {
    throw new RuntimeException("bad size");
}
unlink($path);
unlink($copy);
?>
--EXPECT--
