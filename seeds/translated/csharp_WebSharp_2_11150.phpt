--TEST--
Translated seed (inferredbugs-csharp: WebSharp/2)
--FILE--
<?php
$base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_fileio_' . uniqid('', true);
@mkdir($base);
file_put_contents($base . DIRECTORY_SEPARATOR . 'a.txt', str_repeat("x", 1024));
file_put_contents($base . DIRECTORY_SEPARATOR . 'b.txt', "hello");
@mkdir($base . DIRECTORY_SEPARATOR . 'dir');
@rename($base . DIRECTORY_SEPARATOR . 'b.txt', $base . DIRECTORY_SEPARATOR . 'c.txt');
$entries = scandir($base);
sort($entries);
foreach ($entries as $e) {
    if ($e === '.' || $e === '..') continue;
    $p = $base . DIRECTORY_SEPARATOR . $e;
    if (is_file($p)) {
        $data = file_get_contents($p);
        if (strlen($data) > 0) {
            $data = substr($data, 0, 1) . substr($data, 1);
        }
    } elseif (is_dir($p)) {
        $sub = scandir($p);
        sort($sub);
    }
}
$big = $base . DIRECTORY_SEPARATOR . 'big.txt';
$fh = fopen($big, 'wb');
fseek($fh, 0, SEEK_END);
fwrite($fh, str_repeat("y", 2048));
fflush($fh);
fclose($fh);
clearstatcache();
$st = stat($big);
if ($st !== false && $st['size'] > 0) {
    $chunk = file_get_contents($big, false, null, 0, 17);
}
@unlink($base . DIRECTORY_SEPARATOR . 'a.txt');
@unlink($base . DIRECTORY_SEPARATOR . 'c.txt');
@unlink($big);
@rmdir($base . DIRECTORY_SEPARATOR . 'dir');
@rmdir($base);
?>
--EXPECT--
