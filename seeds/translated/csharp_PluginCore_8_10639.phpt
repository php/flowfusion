--TEST--
Translated seed (inferredbugs-csharp: PluginCore/8)
--FILE--
<?php
$enabledPlugins = ['a', 'b', 'a'];
$loaded = [];
foreach ($enabledPlugins as $pluginId) {
    if (!isset($loaded[$pluginId])) {
        $loaded[$pluginId] = true;
    }
}

$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Plugins_' . bin2hex(random_bytes(4));
@mkdir($root, 0777, true);
@mkdir($root . DIRECTORY_SEPARATOR . 'p1', 0777, true);
file_put_contents($root . DIRECTORY_SEPARATOR . 'p1' . DIRECTORY_SEPARATOR . 'index.html', 'ok');

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($files as $file) {
    if ($file->isFile() && substr($file->getFilename(), -5) === '.html') {
        $content = file_get_contents($file->getPathname());
        if ($content === false) {
            continue;
        }
    }
}

$authSchemes = ['PluginCore.Authentication' => true];
$policy = ['PluginCoreAdmin' => ['PluginCoreAdminRequirement' => true]];
$hasAuth = isset($authSchemes['PluginCore.Authentication']);
$hasPolicy = isset($policy['PluginCoreAdmin']);

echo ($hasAuth && $hasPolicy && count($loaded) === 2) ? '' : '';
?>
--EXPECT--
