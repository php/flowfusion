--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/41)
--FILE--
<?php

function tryGetFileSize($url) {
    $headers = get_headers($url, 1);
    if (isset($headers['Content-Length'])) {
        return (int)$headers['Content-Length'];
    }
    return 0;
}

function isUrlUsable($url) {
    $headers = get_headers($url);
    if (strpos($headers[0], '200') !== false) {
        return 1;
    } elseif (strpos($headers[0], '404') !== false || strpos($headers[0], '403') !== false) {
        return 0;
    }
    return -1;
}

function downloadURL($url, $localPath) {
    $filename = basename($url);
    $localFilePath = rtrim($localPath, '/') . '/' . $filename;
    $fileSize = tryGetFileSize($url);
    if ($fileSize > 0) {
        $output = fopen($localFilePath, 'w');
        $input = fopen($url, 'r');
        if ($input && $output) {
            stream_copy_to_stream($input, $output);
            fclose($input);
            fclose($output);
        }
    }
    return $localFilePath;
}

$url = "http://example.com/file.txt"; // Replace with a valid URL
$localPath = "/path/to/download"; // Replace with a valid directory path
downloadURL($url, $localPath);
--EXPECT--
