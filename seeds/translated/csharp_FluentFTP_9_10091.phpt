--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/9)
--FILE--
<?php
$dt = DateTime::createFromFormat('YmdHis', '20240229123456');
$dt2 = DateTime::createFromFormat('YmdHis.v', '20240229123456.789');
$bad = DateTime::createFromFormat('YmdHis', '20240230123456');
$u = parse_url('ftps://user:pass@example.com:2121/path');
$s = strtolower($u['scheme'] ?? '');
$ok = in_array($s, ['ftp', 'ftps'], true);
$path = $u['path'] ?? '';
$parts = explode('/', trim(str_replace('\\', '/', $path), '/'));
$dir = count($parts) > 1 ? implode('/', array_slice($parts, 0, -1)) : '';
$file = $parts ? end($parts) : '';
$list = ["type=file;name=$file", "type=dir;name=$dir"];
$found = null;
foreach ($list as $line) {
    if (preg_match('/name=([^;]+)/', $line, $m) && $m[1] === $file) {
        $found = $line;
        break;
    }
}
$mode = sprintf('%d%d%d', 7, 5, 5);
$tmp = tempnam(sys_get_temp_dir(), 'ftp');
file_put_contents($tmp, "abcdef");
$fp = fopen($tmp, 'c+b');
fseek($fp, 2);
fwrite($fp, "XY");
rewind($fp);
stream_get_contents($fp);
fclose($fp);
unlink($tmp);
echo ($ok ? '1' : '0') . ($found !== null ? '1' : '0') . $mode . PHP_EOL;
?>
--EXPECT--
