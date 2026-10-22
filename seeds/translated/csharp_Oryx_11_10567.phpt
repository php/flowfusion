--TEST--
Translated seed (inferredbugs-csharp: Oryx/11)
--FILE--
<?php
$base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bug_' . bin2hex(random_bytes(8));
@mkdir($base);
$paths = [
    ['c:/foo', 'c:/foo'],
    ['c:/foo/', 'c:/foo'],
    ['c:/foo', 'c:/foo/'],
    ['c:/foo/', 'c:/foo/'],
    ['c:/foo/bar', 'c:/foo'],
    ['c:/foo/bar/dir1/dir2', 'c:/foo'],
    [DIRECTORY_SEPARATOR . 'foo', DIRECTORY_SEPARATOR],
    [realpath($base . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . 'd' . DIRECTORY_SEPARATOR . '..'), realpath($base . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b')],
    ['c:/Foo', 'c:/foo'],
    ['c:/foo', 'c:/foo/bar'],
    ['a/b/c', 'a/b/cd'],
    [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . 'foo'],
    [realpath($base . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'), realpath($base . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b')],
];
foreach ($paths as [$a, $b]) {
    $na = rtrim(str_replace('\\', '/', (string)$a), '/');
    $nb = rtrim(str_replace('\\', '/', (string)$b), '/');
    $ok = $na === $nb || str_starts_with($na . '/', $nb . '/');
    if ($ok) {
        continue;
    }
}
$src = $base . DIRECTORY_SEPARATOR . 'src';
$dst = $base . DIRECTORY_SEPARATOR . 'dst';
$mid = $src . DIRECTORY_SEPARATOR . 'subdir1' . DIRECTORY_SEPARATOR . 'subdir2';
@mkdir($src, 0777, true);
@mkdir($dst, 0777, true);
@mkdir($mid, 0777, true);
$envNames = ['APP_NAME', 'BUILD_APP_NAME', 'ORYX_APP_NAME'];
putenv($envNames[array_rand($envNames)] . '=bla');
echo "Hello World\n";
echo "done\n";
?>
--EXPECT--
