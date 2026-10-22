--TEST--
Translated seed (inferredbugs-java: WeSync/3)
--FILE--
<?php

function copyFile($sourceFile, $targetFile) {
    $inBuff = fopen($sourceFile, "rb");
    $outBuff = fopen($targetFile, "wb");
    while (!feof($inBuff)) {
        fwrite($outBuff, fread($inBuff, 8192));
    }
    fclose($inBuff);
    fclose($outBuff);
}

function copyDirectory($sourceDir, $targetDir) {
    mkdir($targetDir, 0777, true);
    $files = array_diff(scandir($sourceDir), array('.', '..'));
    foreach ($files as $file) {
        $sourceFile = "$sourceDir/$file";
        $targetFile = "$targetDir/$file";
        if (is_file($sourceFile)) {
            copyFile($sourceFile, $targetFile);
        } elseif (is_dir($sourceFile)) {
            copyDirectory($sourceFile, $targetFile);
        }
    }
}

function getFileMD5($file) {
    if (!is_file($file)) return null;
    $hash = md5_file($file);
    return $hash;
}

function getFileSha1($file) {
    if (!is_file($file)) return null;
    $hash = sha1_file($file);
    return $hash;
}

function clearDirectory($dir) {
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        unlink("$dir/$file");
    }
}

function formatFileSize($fileS) {
    if ($fileS < 1024) return $fileS . "B";
    elseif ($fileS < 1048576) return round($fileS / 1024, 2) . "K";
    elseif ($fileS < 1073741824) return round($fileS / 1048576, 2) . "M";
    else return round($fileS / 1073741824, 2) . "G";
}

$sourceDir = "source_directory"; // specify source directory
$targetDir = "target_directory"; // specify target directory

copyDirectory($sourceDir, $targetDir);
$file = "$targetDir/sample.txt"; // specify a sample file
echo getFileMD5($file);
echo getFileSha1($file);
clearDirectory($targetDir);
echo formatFileSize(filesize($file));
--EXPECT--
