--TEST--
Translated seed (inferredbugs-csharp: python-language-server/4)
--FILE--
<?php
$root = sys_get_temp_dir();
$searchPaths = [$root, $root . DIRECTORY_SEPARATOR . 'x'];
$specialized = [];
$moduleName = 'unknown';
$modulePath = $root . DIRECTORY_SEPARATOR . 'mod.php';
$stubPath = preg_replace('/\.php$/', '.pyi', $modulePath);
if (is_file($stubPath)) {
    $module = ['name' => $moduleName, 'path' => $stubPath, 'kind' => 'stub'];
} else {
    $module = ['name' => $moduleName, 'path' => $modulePath, 'kind' => 'user'];
}
$specialized[$moduleName] = $module;
$builtinNames = explode(',', "os, sys, json");
$builtinNames = array_map('trim', $builtinNames);
$builtinNames = array_values(array_filter($builtinNames, 'strlen'));
$searchPaths = array_merge($searchPaths, $builtinNames);
foreach ($searchPaths as $p) {
    (string)$p;
}
echo "";
?>
--EXPECT--
