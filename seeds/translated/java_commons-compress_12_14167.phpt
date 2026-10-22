--TEST--
Translated seed (inferredbugs-java: commons-compress/12)
--FILE--
<?php

$filePaths = [
    'bla.ar' => 'ArArchiveInputStream',
    'bla.tar' => 'TarArchiveInputStream',
    'bla.zip' => 'ZipArchiveInputStream',
    'bla.jar' => 'JarArchiveInputStream',
    'bla.cpio' => 'CpioArchiveInputStream',
];

foreach ($filePaths as $file => $expected) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $stream = fopen($path, 'rb');
        if ($stream !== false) {
            echo $expected . " detected for " . $file . "\n";
        }
    }
}
--EXPECT--
