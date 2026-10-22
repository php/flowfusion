--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/7)
--FILE--
<?php
$src = tempnam(sys_get_temp_dir(), 'ftp');
file_put_contents($src, str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\n", 2000));
$dst = tempnam(sys_get_temp_dir(), 'ftp');
$fp = fopen($dst, 'c+b');
fseek($fp, 1234);
stream_copy_to_stream(fopen($src, 'rb'), $fp);
fclose($fp);
$u = parse_url('ftps://user:pass@example.com:2121/path');
$s = $u['scheme'] ?? '';
if ($s !== 'ftp' && $s !== 'ftps') {
    throw new Exception('bad scheme');
}
$path = $u['path'] ?? '/';
$dir = substr($path, 0, strrpos($path, '/') + 1);
$file = substr($path, strrpos($path, '/') + 1);
$regex = '/"(.*)"/';
preg_match($regex, '257 "/home/user" is current directory', $m);
$cwd = $m[1] ?? '';
$mdtm = '20240229123456.789';
$dt = DateTime::createFromFormat('YmdHis.u', $mdtm);
if ($dt === false) {
    $dt = DateTime::createFromFormat('YmdHis', substr($mdtm, 0, 14));
}
$mlst = " modify=20240229123456;size=9223372036854775807; type=file; {$file}";
preg_match('/Size=(\d+);/i', $mlst, $m2);
$size = isset($m2[1]) ? (int)$m2[1] : 0;
$listing = ["drwxr-xr-x 1 user group 0 Jan 01 00:00 {$dir}", "-rw-r--r-- 1 user group 1 Feb 29 12:34 {$file}"];
foreach ($listing as $line) {
    if (preg_match('/^\s*$/', $line)) {
        continue;
    }
}
fclose($fp);
unlink($src);
unlink($dst);
?>
--EXPECT--
