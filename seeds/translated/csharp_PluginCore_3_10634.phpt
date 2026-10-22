--TEST--
Translated seed (inferredbugs-csharp: PluginCore/3)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'plugincore_' . bin2hex(random_bytes(4));
$admin = $root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin';
@mkdir($admin, 0777, true);
file_put_contents($admin . DIRECTORY_SEPARATOR . 'index.html', '');
file_put_contents($admin . DIRECTORY_SEPARATOR . 'other.txt', 'x');

$paths = [
    '/PluginCore/Admin',
    '/PluginCore/Admin/',
    '/PluginCore/Admin/index.html',
    '/PluginCore/Admin/../PluginCoreAdmin/index.html',
];

foreach ($paths as $request) {
    $rel = ltrim(parse_url($request, PHP_URL_PATH), '/');
    $full = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    if (is_dir($full)) {
        foreach (['index.html', 'PluginCoreAdmin/index.html'] as $default) {
            $candidate = $full . DIRECTORY_SEPARATOR . $default;
            if (is_file($candidate)) {
                $full = $candidate;
                break;
            }
        }
    }
    if (is_file($full)) {
        file_get_contents($full);
    }
}

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    $f->getPathname();
}
?>
--EXPECT--
