--TEST--
Translated seed (inferredbugs-csharp: LibOrbisPkg/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_pkgeditor_' . bin2hex(random_bytes(4));
@mkdir($dir);
$gp4 = $dir . DIRECTORY_SEPARATOR . 'a.gp4';
$pkg = $dir . DIRECTORY_SEPARATOR . 'b.pkg';
file_put_contents($gp4, str_repeat("GP4\0", 8) . "\x00\x01\x02\x03");
file_put_contents($pkg, str_repeat("PKG\0", 8) . "\x04\x05\x06\x07");

foreach ([$gp4, $pkg] as $f) {
    $h = fopen($f, 'rb');
    if ($h !== false) {
        $data = stream_get_contents($h);
        fclose($h);
        $name = basename($f);
        $len = strlen($data);
        $first = $len ? ord($data[0]) : 0;
        $last = $len ? ord($data[$len - 1]) : 0;
        $mix = ($first ^ $last ^ $len) & 255;
        $tab = [$name => [$len, $mix]];
        $tab[$name][0] += 1;
    }
}

?>
--EXPECT--
