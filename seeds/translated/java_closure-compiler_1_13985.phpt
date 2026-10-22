--TEST--
Translated seed (inferredbugs-java: closure-compiler/1)
--FILE--
<?php
$outputFile = 'output.js';
$warningLevel = 'default';
$debugOptions = false;
$compilationLevel = 'simple';

if (!file_exists(dirname($outputFile))) {
    mkdir(dirname($outputFile), 0777, true);
}

$externs = [];
$sources = [];

$sourceFileList = ['source1.js', 'source2.js'];
$externFileList = ['extern1.js', 'extern2.js'];

foreach ($externFileList as $file) {
    $externs[] = file_get_contents($file);
}

foreach ($sourceFileList as $file) {
    $sources[] = file_get_contents($file);
}

echo "Compiling " . count($sources) . " file(s) with " . count($externs) . " extern(s)\n";

$result = implode("\n", $externs) . "\n" . implode("\n", $sources);
file_put_contents($outputFile, $result);
?>
--EXPECT--
