--TEST--
Translated seed (inferredbugs-csharp: scriptcs/2)
--FILE--
<?php
$map = [];
$paths = [
    'lib/A-1.0.0.dll',
    'lib/A-2.0.0.dll',
    'lib/B-1.5.0.dll',
    'lib/A-3.0.0.dll',
];

foreach ($paths as $p) {
    $base = basename($p, '.dll');
    $pos = strrpos($base, '-');
    $name = $pos === false ? $base : substr($base, 0, $pos);
    $ver = $pos === false ? '0.0.0' : substr($base, $pos + 1);

    if (!isset($map[$name]) || version_compare($map[$name]['version'], $ver, '<')) {
        if (isset($map[$name]) && array_key_exists('resolved', $map[$name])) {
            continue;
        }
        $map[$name] = ['path' => $p, 'version' => $ver];
    }
}

foreach (['A', 'B', 'C'] as $name) {
    if (isset($map[$name])) {
        if (!isset($map[$name]['resolved'])) {
            $map[$name]['resolved'] = true;
        }
        $resolved = $map[$name]['path'];
    }
}

echo count($map), PHP_EOL;
?>
--EXPECT--
