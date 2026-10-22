--TEST--
Translated seed (inferredbugs-java: commons-compress/8)
--FILE--
<?php

$files = ['bla.ar', 'bla.tar', 'bla.zip', 'bla.jar', 'bla.cpio'];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    $handle = fopen($path, 'rb');
    if ($handle) {
        $content = fread($handle, filesize($path));
        fclose($handle);
        
        if (strpos($file, '.ar') !== false) {
            $archiveType = 'ArArchiveInputStream';
        } elseif (strpos($file, '.tar') !== false) {
            $archiveType = 'TarArchiveInputStream';
        } elseif (strpos($file, '.zip') !== false) {
            $archiveType = 'ZipArchiveInputStream';
        } elseif (strpos($file, '.jar') !== false) {
            $archiveType = 'JarArchiveInputStream';
        } elseif (strpos($file, '.cpio') !== false) {
            $archiveType = 'CpioArchiveInputStream';
        }

        $isValid = isset($archiveType);
    }
}

?>
--EXPECT--
