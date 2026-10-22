--TEST--
Translated seed (inferredbugs-csharp: StarBlog/1)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_photo_service_' . bin2hex(random_bytes(4));
@mkdir($root . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'photography', 0777, true);

$id = bin2hex(random_bytes(8));
$filePath = 'photography' . DIRECTORY_SEPARATOR . $id . '.jpg';
$savePath = $root . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $filePath;

file_put_contents($savePath, "x");
if (file_exists($savePath)) unlink($savePath);

$combined = $root . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $filePath;
echo $combined;
?>
--EXPECT--
