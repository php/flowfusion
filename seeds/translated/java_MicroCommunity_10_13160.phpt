--TEST--
Translated seed (inferredbugs-java: MicroCommunity/10)
--FILE--
<?php

$fileDto = new stdClass();
$fileDto->fileSaveName = "folder/file.jpg"; // Simulating a file name with a path
$ftpPath = "/home/hc/img/";
$fileName = $fileDto->fileSaveName;

if (strpos($fileName, "/") !== false) {
    $ftpPath .= substr($fileName, 0, strrpos($fileName, "/") + 1);
    $fileName = substr($fileName, strrpos($fileName, "/") + 1);
}

$fileImg = str_repeat("A", 1024); // Simulating byte data from FTP upload

$file = $ftpPath . uniqid() . ".jpg";

if (!file_exists($ftpPath)) {
    mkdir($ftpPath, 0777, true);
}

file_put_contents($file, $fileImg);

$context = base64_encode($fileImg);

$fileDto->context = $context;
echo json_encode([$fileDto]);
--EXPECT--
