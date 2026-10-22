--TEST--
Translated seed (inferredbugs-csharp: PluginCore/4)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'PluginCoreAdmin_' . bin2hex(random_bytes(4));
@mkdir($root, 0777, true);
@mkdir($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin', 0777, true);
file_put_contents($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . 'index.html', 'ok');

$path = $root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));
$map = [];
foreach ($files as $f) {
    $rel = substr($f->getPathname(), strlen($path) + 1);
    $map[str_replace(DIRECTORY_SEPARATOR, '/', $rel)] = file_get_contents($f->getPathname());
}

$requestPath = '/PluginCore/Admin';
$uri = $requestPath . '/index.html';
$prefix = '/PluginCore/Admin';
if (str_starts_with($uri, $prefix)) {
    $rel = ltrim(substr($uri, strlen($prefix)), '/');
    $content = $map[$rel] ?? null;
    if ($content === null && $rel === '') $content = $map['index.html'] ?? null;
    if ($content !== null) echo $content;
}
?>
--EXPECT--
