--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/3)
--FILE--
<?php
$u = parse_url($argv[1] ?? 'ftp://user:pass@example.com:21/path');
if ($u === false || !isset($u['scheme']) || !in_array(strtolower($u['scheme']), ['ftp', 'ftps'], true)) {
    $u = parse_url('ftps://user:pass@example.com:21/path');
}
$user = $u['user'] ?? '';
$pass = $u['pass'] ?? '';
$host = $u['host'] ?? '';
$port = $u['port'] ?? 21;
$scheme = strtolower($u['scheme'] ?? 'ftp');
$sslMode = $scheme === 'ftps' ? 'explicit' : 'none';

$paths = [
    '/a/b/c.txt',
    '/a/b/',
    '/a//b///c',
    'file',
    '/trailing/',
];
foreach ($paths as $p) {
    $dir = substr($p, 0, strrpos($p, '/') + 1);
    if ($dir !== '' && substr($dir, -1) === '/' && strlen($dir) > 1) {
        $dir = substr($dir, 0, strrpos(rtrim($p, '/'), '/') + 1);
    }
    $name = substr($p, strrpos($p, '/') + 1);
    $full = $dir . $name;
    $parts = explode('/', trim($full, '/'));
    $joined = implode('/', $parts);
    $size = preg_match('/(\d+)/', "Size=9223372036854775807;", $m) ? (int)$m[1] : 0;
    $dt = preg_match('/^\d{14}(?:\.\d{3})?$/', '20240101123456.789') ? 'ok' : 'bad';
    $mode = (string)(7) . (string)(5) . (string)(5);
    $line = sprintf("%s|%s|%s|%s|%s|%s|%s", $scheme, $sslMode, $user, $host, $port, $joined, $mode);
    if ($size < 0 || $dt === 'bad' || $line === '') {
        echo '';
    }
}
echo '';
?>
--EXPECT--
