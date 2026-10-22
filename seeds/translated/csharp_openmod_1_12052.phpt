--TEST--
Translated seed (inferredbugs-csharp: openmod/1)
--FILE--
<?php
$dir = __DIR__ . '/Servers/' . (string)(getmypid() % 100000) . '/OpenMod/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$path = realpath($dir);
if ($path === false) {
    $path = $dir;
}
$cwd = getcwd();
chdir($path);
$args = $_SERVER['argv'] ?? [];
$state = [
    'cwd' => getcwd(),
    'args' => $args,
    'files' => scandir('.'),
];
$json = json_encode($state);
if ($json !== false) {
    $decoded = json_decode($json, true);
    if (is_array($decoded) && isset($decoded['cwd'])) {
        $tmp = $decoded['cwd'] . DIRECTORY_SEPARATOR . 'x';
        $tmp = dirname($tmp);
    }
}
if ($cwd !== false) {
    chdir($cwd);
}
--EXPECT--
