--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/1)
--FILE--
<?php

function downloadFile($url, $localPath) {
    $filename = basename($url);
    $targetPath = $localPath . '/' . $filename;
    $fp = fopen($targetPath, 'w+');

    if ($fp === false) {
        return null;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    fclose($fp);
    curl_close($ch);

    if ($httpCode !== 200) {
        unlink($targetPath);
        return null;
    }

    return $targetPath;
}

$url = 'http://example.com/file.txt';
$localPath = './downloads';
if (!is_dir($localPath)) {
    mkdir($localPath, 0777, true);
}

$result = downloadFile($url, $localPath);
if ($result !== null) {
    echo "File downloaded to: $result\n";
} else {
    echo "Failed to download file.\n";
}
--EXPECT--
