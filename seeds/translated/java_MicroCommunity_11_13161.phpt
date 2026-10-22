--TEST--
Translated seed (inferredbugs-java: MicroCommunity/11)
--FILE--
<?php

$fileDto = new stdClass();
$fileDto->context = str_repeat("A", 1048576); // 1MB of data
$ftpPath = '/home/hc/img/';
$fileName = uniqid() . ".jpg";

if (!file_exists($ftpPath)) {
    mkdir($ftpPath, 0777, true);
}

$file = $ftpPath . $fileName;
file_put_contents($file, $fileDto->context);

$fileImg = file_get_contents($file);
$context = base64_encode($fileImg);
$fileDto->context = $context;

echo $fileDto->context;

?>
--EXPECT--
