--TEST--
Translated seed (inferredbugs-java: HotswapAgent/7)
--FILE--
<?php

$path = 'path_to_your_directory_or_jar'; // Specify your path

function scan($path) {
    if (is_dir($path)) {
        scanDirectory($path);
    } elseif (is_file($path) && preg_match('/\.jar$/', $path)) {
        scanJar($path);
    } else {
        echo "Unknown resource type of file $path\n";
    }
}

function scanDirectory($directory) {
    foreach (scandir($directory) as $file) {
        if ($file === '.' || $file === '..') continue;
        $filePath = $directory . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            scanDirectory($filePath);
        } elseif (is_file($filePath) && preg_match('/\.class$/', $file)) {
            // Simulating visiting class file
            echo "Visiting class file $filePath\n";
        }
    }
}

function scanJar($jarFile) {
    echo "Scanning JAR file '$jarFile'\n";
    $zip = new ZipArchive;
    if ($zip->open($jarFile) === TRUE) {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            if (preg_match('/\.class$/', $entry)) {
                echo "Visiting JAR entry $entry\n";
                // Simulating visiting jar entry
            }
        }
        $zip->close();
    } else {
        echo "Failed to open JAR file $jarFile\n";
    }
}

scan($path);
--EXPECT--
