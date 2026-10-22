--TEST--
Translated seed (inferredbugs-csharp: ScheduleMasterCore/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bug_' . bin2hex(random_bytes(4));
mkdir($dir);
$path = $dir . DIRECTORY_SEPARATOR . "a\\b.txt";
file_put_contents($path, str_repeat("x", 3));
$fp = fopen($path, 'rb');
stream_copy_to_stream($fp, fopen('php://temp', 'wb'));
fclose($fp);
$files = [];
$files["file"] = ["name" => "a\\b.txt", "tmp_name" => $path, "size" => filesize($path), "error" => UPLOAD_ERR_OK, "type" => "text/plain"];
$_FILES = $files;
if (isset($_FILES["file"]) && $_FILES["file"]["size"] > 0) {
    $target = $dir . DIRECTORY_SEPARATOR . $_FILES["file"]["name"];
    copy($_FILES["file"]["tmp_name"], $target);
}
echo "ok";
?>
--EXPECT--
