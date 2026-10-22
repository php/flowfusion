--TEST--
Translated seed (inferredbugs-java: arthas/6)
--FILE--
<?php

$sourcefiles = ['/tmp/Test.java'];
$directory = null;
$hashCode = null;
$encoding = 'UTF-8';

$exitCode = 0;
$affect = 0;

try {
    $byteCodes = [];
    foreach ($sourcefiles as $sourceFile) {
        $sourceCode = file_get_contents($sourceFile);
        $name = basename($sourceFile, '.java');
        $byteCodes[$name] = $sourceCode; // Simulate bytecode creation
    }

    $outputDir = $directory ? $directory : getcwd();
    echo "Memory compiler output:\n";
    foreach ($byteCodes as $key => $value) {
        $byteCodeFile = $outputDir . '/' . $key . '.class';
        file_put_contents($byteCodeFile, $value); // Simulate writing bytecode file
        echo $byteCodeFile . "\n";
        $affect++;
    }
} catch (Throwable $e) {
    echo "Memory compiler error, exception message: " . $e->getMessage() . "\n";
    $exitCode = -1;
} finally {
    echo "Rows affected: " . $affect . "\n";
    exit($exitCode);
}
--EXPECT--
