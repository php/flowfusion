--TEST--
Translated seed (inferredbugs-csharp: PluginCore/7)
--FILE--
<?php
$enabledPlugins = ['a', 'b', 'a'];
$loaded = [];
foreach ($enabledPlugins as $pluginId) {
    if (!isset($loaded[$pluginId])) {
        $loaded[$pluginId] = true;
    }
}
$authSchemes = [];
$authSchemes[] = 'PluginCore.Authentication';
$authSchemes[] = 'PluginCore.Authentication';
$authCount = 0;
foreach ($authSchemes as $scheme) {
    if ($scheme === 'PluginCore.Authentication') {
        $authCount++;
        break;
    }
}
$authorizationCount = 0;
for ($i = 0; $i < 2; $i++) {
    $authorizationCount++;
}
$pluginWwwRootDir = __DIR__ . DIRECTORY_SEPARATOR . 'Plugins_wwwroot';
if (!is_dir($pluginWwwRootDir)) {
    mkdir($pluginWwwRootDir, 0777, true);
}
$files = glob($pluginWwwRootDir . DIRECTORY_SEPARATOR . '*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}
echo "OK\n";
--EXPECT--
