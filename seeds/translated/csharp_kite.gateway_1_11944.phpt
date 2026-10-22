--TEST--
Translated seed (inferredbugs-csharp: kite.gateway/1)
--FILE--
<?php
$modules = [
    ['id' => 'a', 'isApplicationPart' => true, 'assembly' => null, 'viewsAssembly' => null],
    ['id' => 'b', 'isApplicationPart' => true, 'assembly' => null, 'viewsAssembly' => null],
    ['id' => 'c', 'isApplicationPart' => false, 'assembly' => null, 'viewsAssembly' => null],
];

$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_module_bug_' . uniqid();
@mkdir($root);
foreach ($modules as $m) {
    $dir = $root . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $m['id'];
    @mkdir($dir, 0777, true);
    if ($m['id'] !== 'b') file_put_contents($dir . DIRECTORY_SEPARATOR . $m['id'] . '.dll', 'x');
    if ($m['id'] === 'a') file_put_contents($dir . DIRECTORY_SEPARATOR . $m['id'] . '.Views.dll', 'y');
    file_put_contents($dir . DIRECTORY_SEPARATOR . $m['id'] . '.xml', '<root/>');
}

$paths = [];
foreach ($modules as $module) {
    if (!$module['isApplicationPart']) continue;
    $xmlPath = $root . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $module['id'] . DIRECTORY_SEPARATOR . $module['id'] . '.xml';
    $paths[] = $xmlPath;
    $dllPath = $root . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $module['id'] . DIRECTORY_SEPARATOR . $module['id'] . '.dll';
    if (is_file($dllPath)) {
        $h = fopen($dllPath, 'rb');
        fread($h, 1);
        fclose($h);
    }
    $viewsPath = $root . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $module['id'] . DIRECTORY_SEPARATOR . $module['id'] . '.Views.dll';
    if (is_file($viewsPath)) {
        $h = fopen($viewsPath, 'rb');
        fread($h, 1);
        fclose($h);
    }
}

$selected = [];
foreach ($modules as $module) {
    if ($module['isApplicationPart'] && strpos($module['id'], 'a') !== false) {
        $selected[] = $module['id'];
    }
}

echo count($paths) . ':' . count($selected);
?>
--EXPECT--
