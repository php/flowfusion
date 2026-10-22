--TEST--
Translated seed (inferredbugs-java: commons-compress/11)
--FILE--
<?php
$filePaths = [
    'bla.ar',
    'bla.tar',
    'bla.zip',
    'bla.jar',
    'bla.cpio'
];

foreach ($filePaths as $filePath) {
    $stream = fopen(__DIR__ . '/' . $filePath, 'rb');
    if ($stream === false) {
        die("Failed to open $filePath");
    }
    $bufferedStream = stream_get_contents($stream);
    fclose($stream);
}
?>
--EXPECT--
