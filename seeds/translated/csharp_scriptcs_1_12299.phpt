--TEST--
Translated seed (inferredbugs-csharp: scriptcs/1)
--FILE--
<?php
$map = [];
$paths = [
    ['name' => 'Foo', 'version' => [1, 0, 0, 0], 'path' => '/tmp/Foo-v1.dll'],
    ['name' => 'Foo', 'version' => [2, 0, 0, 0], 'path' => '/tmp/Foo-v2.dll'],
    ['name' => 'Bar', 'version' => [1, 5, 0, 0], 'path' => '/tmp/Bar-v1.dll'],
    ['name' => 'Bar', 'version' => [1, 4, 9, 9], 'path' => '/tmp/Bar-v0.dll'],
    ['name' => 'Baz', 'version' => [0, 9, 9, 9], 'path' => '/tmp/Baz.dll'],
];

foreach ($paths as $info) {
    $name = $info['name'];
    $v = $info['version'];
    if (!isset($map[$name]) || $map[$name]['version'] < $v) {
        $map[$name] = ['path' => $info['path'], 'version' => $v, 'loaded' => null];
    }
}

$requests = ['Foo', 'Bar', 'Baz', 'Missing', 'Foo', 'Bar'];
foreach ($requests as $name) {
    if (isset($map[$name])) {
        if ($map[$name]['loaded'] === null) {
            $map[$name]['loaded'] = $map[$name]['path'];
        }
        $resolved = $map[$name]['loaded'];
        $version = implode('.', $map[$name]['version']);
        $resolved . $version;
    }
}
echo "ok\n";
?>
--EXPECT--
