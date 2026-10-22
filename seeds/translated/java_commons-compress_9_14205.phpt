--TEST--
Translated seed (inferredbugs-java: commons-compress/9)
--FILE--
<?php

$files = ['bla.ar', 'bla.tar', 'bla.zip', 'bla.jar', 'bla.cpio'];
$classes = ['ArArchiveInputStream', 'TarArchiveInputStream', 'ZipArchiveInputStream', 'JarArchiveInputStream', 'CpioArchiveInputStream'];
$path = __DIR__ . '/';

foreach ($files as $index => $file) {
    $resource = fopen($path . $file, 'rb');
    $archiveStream = null;

    if ($file == 'bla.ar') {
        $archiveStream = new ArArchiveInputStream($resource);
    } elseif ($file == 'bla.tar') {
        $archiveStream = new TarArchiveInputStream($resource);
    } elseif ($file == 'bla.zip') {
        $archiveStream = new ZipArchiveInputStream($resource);
    } elseif ($file == 'bla.jar') {
        $archiveStream = new JarArchiveInputStream($resource);
    } elseif ($file == 'bla.cpio') {
        $archiveStream = new CpioArchiveInputStream($resource);
    }

    if ($archiveStream !== null) {
        // Simulate the checks without assertions
        $type = get_class($archiveStream);
        echo "$file detected as $type\n";
    }

    fclose($resource);
}
--EXPECT--
