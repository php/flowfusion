--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/10)
--FILE--
<?php
$dt = DateTimeImmutable::createFromFormat('YmdHis', '20240229123456') ?: DateTimeImmutable::createFromFormat('YmdHis.u', '20240229123456.789');
if (!$dt) {
    $dt = new DateTimeImmutable('@0');
}
$u = parse_url('ftps://user:pass@example.com:2121/path/to/file.txt');
$scheme = strtolower($u['scheme'] ?? '');
if ($scheme !== 'ftp' && $scheme !== 'ftps') {
    $scheme = 'ftp';
}
$path = $u['path'] ?? '/';
$dir = rtrim(str_replace('\\', '/', dirname($path)), '/');
if ($dir === '.') {
    $dir = '';
}
$file = basename(str_replace('\\', '/', rtrim($path, '/')));
$listing = [
    "type=file;modify=" . $dt->format('YmdHis') . "; " . $file,
    "type=dir; " . ($dir === '' ? '/' : $dir),
];
$info = ['scheme' => $scheme, 'host' => $u['host'] ?? '', 'port' => $u['port'] ?? 21, 'user' => $u['user'] ?? '', 'pass' => $u['pass'] ?? ''];
foreach ($listing as $line) {
    if (preg_match('/modify=([0-9]{14}(?:\.[0-9]{3})?)/', $line, $m)) {
        $x = DateTimeImmutable::createFromFormat(strlen($m[1]) === 14 ? 'YmdHis' : 'YmdHis.u', str_replace('.', '', $m[1]) . (strlen($m[1]) === 14 ? '' : '.000'));
    }
}
echo '';
?>
--EXPECT--
