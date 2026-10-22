--TEST--
Translated seed (inferredbugs-csharp: LiteDB/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_litedb_' . bin2hex(random_bytes(4));
@mkdir($dir);
$file = $dir . DIRECTORY_SEPARATOR . 'test.db';

$f = fopen($file, 'wb');
fwrite($f, random_bytes(64));
fclose($f);

$h = @fopen($file, 'rb');
if ($h !== false) {
    $data = fread($h, 64);
    fclose($h);
    $s = json_encode(['data' => $data, 'len' => strlen($data)], JSON_UNESCAPED_SLASHES);
    if (is_string($s)) {
        echo $s, PHP_EOL;
    }
}

$nested = [
    null,
    ['a' => 1, 'b' => [1, 2, 3]],
    ['x', 'y', 'z'],
    "text",
    123,
];
echo json_encode($nested, JSON_UNESCAPED_SLASHES), PHP_EOL;

@unlink($file);
@rmdir($dir);
?>
--EXPECT--
