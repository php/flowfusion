--TEST--
Translated seed (inferredbugs-csharp: Oryx/13)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_py_detect_' . bin2hex(random_bytes(4));
mkdir($root);
$cases = [
    [],
    ['foo.py' => 'foo.py content'],
    ['requirements.txt' => 'requirements.txt content'],
    ['requirements.txt' => '', 'runtime.txt' => 'python-3.7.0'],
    ['requirements.txt' => '', 'runtime.txt' => 'foo'],
    ['requirements.txt' => '', 'runtime.txt' => 'python'],
    ['requirements.txt' => 'content', 'foo.py' => 'foo.py content'],
];
foreach ($cases as $i => $files) {
    $dir = $root . DIRECTORY_SEPARATOR . $i;
    mkdir($dir);
    foreach ($files as $name => $content) file_put_contents($dir . DIRECTORY_SEPARATOR . $name, $content);
    $hasPy = false;
    $runtime = null;
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        if (substr($f, -3) === '.py') $hasPy = true;
        if ($f === 'runtime.txt') $runtime = trim(file_get_contents($dir . DIRECTORY_SEPARATOR . $f));
    }
    $result = null;
    if ($hasPy || is_file($dir . DIRECTORY_SEPARATOR . 'runtime.txt')) {
        if ($runtime === null || $runtime === '' || $runtime === 'foo' || $runtime === 'python') {
            $result = null;
        } elseif ($runtime === 'python-3.7.0') {
            $result = ['Language' => 'python', 'LanguageVersion' => '3.7.0'];
        } elseif ($runtime === 'python-100.100.100') {
            throw new RuntimeException("Platform 'python' version '100.100.100' is unsupported. Supported versions: 3.7.0");
        } else {
            $result = ['Language' => 'python', 'LanguageVersion' => '3.7.0'];
        }
    }
}
echo "";
?>
--EXPECT--
