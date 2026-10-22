--TEST--
Translated seed (inferredbugs-csharp: pkNX/2)
--FILE--
<?php
$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'large_container_' . uniqid('', true) . '.bin';
file_put_contents($path, "abcdef");

$fh = fopen($path, 'rb');
fseek($fh, 2);
$data1 = fread($fh, 2);
fseek($fh, -1, SEEK_CUR);
$data2 = fread($fh, 1);
fclose($fh);

$copy = $data1;
$copy[0] = chr(ord($copy[0]) ^ 1);

$cache = [null, null, null];
for ($i = 0; $i < count($cache); $i++) {
    if ($cache[$i] === null) {
        $cache[$i] = substr("xyz", $i, 1);
    }
}

if (file_exists($path)) {
    unlink($path);
}
?>
--EXPECT--
