--TEST--
Translated seed (inferredbugs-csharp: StrongNameSigner/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_signing_helper_' . uniqid('', true);
mkdir($dir);
$assembly = $dir . DIRECTORY_SEPARATOR . 'a.dll';
$key = $dir . DIRECTORY_SEPARATOR . 'k.snk';
file_put_contents($assembly, '');
file_put_contents($key, '');

$path = trim($assembly);
if ($path === '') {
    throw new RuntimeException('assemblyPath');
}
if (!file_exists($path)) {
    throw new RuntimeException('Could not find provided assembly file.');
}

$outputPath = '';
if (trim($outputPath) === '') {
    $outputPath = dirname($path);
}

$info = ['path' => $path, 'signed' => false];
if ($info['signed']) {
    throw new RuntimeException("The assembly '{$path}' is already strong-name signed.");
}

if ($key === '') {
    $key = $dir . DIRECTORY_SEPARATOR . 'generated.snk';
    file_put_contents($key, '');
} elseif (!file_exists($key)) {
    throw new RuntimeException('Could not find provided strong-name key file file.');
}

$target = $outputPath . DIRECTORY_SEPARATOR . basename($path);
copy($path, $target);
$info2 = ['path' => $target, 'signed' => true];

if (!file_exists($info2['path'])) {
    throw new RuntimeException("CORFLAGS failed to execute for assembly '{$path}'.");
}
echo '';
?>
--EXPECT--
