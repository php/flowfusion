--TEST--
Translated seed (inferredbugs-java: de.flapdoodle.embed.process/2)
--FILE--
<?php

$bufferLength = 1024 * 16;

$tempDir = sys_get_temp_dir();
$tempFileName = uniqid('temp_', true) . '.txt';
$tempFile = $tempDir . DIRECTORY_SEPARATOR . $tempFileName;

// Create temp file
if (file_exists($tempFile)) {
    throw new Exception("could not create $tempFile, file already exists");
}
file_put_contents($tempFile, '');

// Write to the temp file
$content = str_repeat('a', 10000);
file_put_contents($tempFile, $content);

// Move the temp file to a new location
$destination = $tempDir . DIRECTORY_SEPARATOR . 'moved_' . $tempFileName;
if (!rename($tempFile, $destination)) {
    copy($tempFile, $destination);
    unlink($tempFile);
}

// Clean exit
exit(0);
--EXPECT--
