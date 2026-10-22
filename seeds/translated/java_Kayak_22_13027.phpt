--TEST--
Translated seed (inferredbugs-java: Kayak/22)
--FILE--
<?php

$file = 'logfile.log';
$compressed = false;
$description = '';
$platform = '';
$deviceAlias = [];
$length = filesize($file);

if (substr($file, -7) === '.log.gz') {
    $compressed = true;
    $inputStream = gzopen($file, 'rb');
} else {
    $inputStream = fopen($file, 'rb');
}

function parseHeader($inputStream) {
    global $description, $platform, $deviceAlias;
    while (($line = fgets($inputStream)) !== false) {
        if (preg_match('/DESCRIPTION "([a-zA-Z0-9\s]+)"/', $line, $matches)) {
            $description = $matches[1];
        } elseif (preg_match('/PLATFORM ([A-Z0-9_]+)/', $line, $matches)) {
            $platform = $matches[1];
        } elseif (preg_match('/DEVICE_ALIAS ([A-Za-z0-9]+) ([a-z0-9]{1,16})/', $line, $matches)) {
            $deviceAlias[$matches[2]] = $matches[1];
        } elseif (trim($line) !== '') {
            if ($description === '') {
                $description = basename($file);
            }
            if ($platform === '') {
                $platform = 'No platform';
            }
            break;
        }
    }
}

parseHeader($inputStream);
fclose($inputStream); 

$fileTemp = $file . '.tmp';
$fileHandle = fopen($fileTemp, 'w');

if ($compressed) {
    $inputStream = gzopen($file, 'rb');
} else {
    $inputStream = fopen($file, 'rb');
}

$written = false;
while (($line = fgets($inputStream)) !== false) {
    if (!$written && strpos($line, 'PLATFORM') === 0) {
        fwrite($fileHandle, "PLATFORM $platform\n");
        $written = true;
    } elseif (!$written && strpos($line, '(') === 0) {
        fwrite($fileHandle, "PLATFORM $platform\n");
        fwrite($fileHandle, $line);
        $written = true;
    } else {
        fwrite($fileHandle, $line);
    }
}

fclose($fileHandle);
fclose($inputStream);

if (!unlink($file)) {
    error_log("Could not delete old file");
}

if (!rename($fileTemp, $file)) {
    error_log("Could not rename new file to old filename");
} 

$platform = $platform;
$description = $description;
--EXPECT--
