--TEST--
Translated seed (inferredbugs-csharp: TcNo-Acc-Switcher/4)
--FILE--
<?php
declare(strict_types=1);

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bugpattern_' . bin2hex(random_bytes(4));
mkdir($dir, 0777, true);

$file = $dir . DIRECTORY_SEPARATOR . 'settings.json';
file_put_contents($file, "{\n  \"FolderPath\": \"C:\\temp\\x\",\n  \"arr\": [1,2,3]\n}\n");

$txt = file($file, FILE_IGNORE_NEW_LINES);
if ($txt !== false) {
    foreach ($txt as &$line) {
        if (str_contains($line, 'FolderPath')) {
            $line = preg_replace('/(?<!\\\\)\\\\(?!\\\\)/', '\\\\', $line);
        }
    }
    unset($line);
    file_put_contents($file, implode(PHP_EOL, $txt));
}

$data = json_decode((string)file_get_contents($file), true);
if (!is_array($data)) {
    $data = [];
}
$defaults = ['FolderPath' => '', 'missing' => 1, 'arr' => [3, 4]];
foreach ($defaults as $k => $v) {
    if (!array_key_exists($k, $data)) {
        $data[$k] = $v;
    }
}
file_put_contents($file, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

$copy = $dir . DIRECTORY_SEPARATOR . 'copy';
mkdir($copy, 0777, true);
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $p => $info) {
    $dst = $copy . substr($p, strlen($dir));
    if ($info->isDir()) {
        if (!is_dir($dst)) mkdir($dst, 0777, true);
    } else {
        copy($p, $dst);
    }
}

$hash = is_file($file) ? md5_file($file) : '0';
echo $hash . PHP_EOL;
--EXPECT--
