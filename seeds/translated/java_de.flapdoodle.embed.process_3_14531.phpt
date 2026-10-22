--TEST--
Translated seed (inferredbugs-java: de.flapdoodle.embed.process/3)
--FILE--
<?php
$tempDir = sys_get_temp_dir();
$tempFileName = uniqid('tempfile_', true);
$tempFilePath = $tempDir . DIRECTORY_SEPARATOR . $tempFileName;

if (file_exists($tempFilePath)) {
    throw new Exception("File already exists: $tempFilePath");
}

$fileCreated = touch($tempFilePath);
if (!$fileCreated) {
    throw new Exception("Could not create file: $tempFilePath");
}

$content = str_repeat("Hello, World!\n", 1000);
file_put_contents($tempFilePath, $content);

$destinationPath = $tempDir . DIRECTORY_SEPARATOR . 'moved_' . $tempFileName;
if (!rename($tempFilePath, $destinationPath)) {
    copy($destinationPath, $tempFilePath);
    unlink($destinationPath);
}

unlink($destinationPath);
--EXPECT--
