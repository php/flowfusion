--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/38)
--FILE--
<?php

$DOWNLOAD_BUFFER_SIZE = 153600;

function tryGetFileSize($url) {
    $headers = get_headers($url, 1);
    if (!isset($headers['Content-Length'])) {
        return -1;
    }
    return (int)$headers['Content-Length'];
}

function downloadURL($url, $localPath) {
    $pathParts = explode('/', parse_url($url, PHP_URL_PATH));
    $filename = end($pathParts);
    $targetPath = null;
    $fullpath = $localPath;

    if (!is_dir($fullpath)) {
        if (!mkdir($fullpath, 0777, true)) {
            return null;
        }
    }

    $srcLength = tryGetFileSize($url);
    if ($srcLength < 0) {
        $srcLength = 0;
    }

    $fullpath = $localPath . '/' . $filename;
    $targetPath = realpath($fullpath);
    $totalBytesRead = 0;

    $reader = fopen($url, 'rb');
    if (!$reader) {
        return null;
    }
    
    $writer = fopen($fullpath, 'wb');
    if (!$writer) {
        fclose($reader);
        return null;
    }

    while (!feof($reader)) {
        $buffer = fread($reader, $DOWNLOAD_BUFFER_SIZE);
        $totalBytesRead += fwrite($writer, $buffer);
    }

    fclose($reader);
    fclose($writer);
    return $targetPath;
}

$url = "https://example.com/path/to/file"; // replace with a valid URL
$localPath = "./downloads"; // specify a local path
downloadURL($url, $localPath);

?>
--EXPECT--
