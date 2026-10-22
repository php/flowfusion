--TEST--
Translated seed (inferredbugs-java: arthas/5)
--FILE--
<?php

$sourceFiles = $argv[1] ?? null;
$directory = $argv[2] ?? null;

if ($sourceFiles === null) {
    die("Source files not specified.\n");
}

$sourceFiles = explode(',', $sourceFiles);
$byteCodes = [];

foreach ($sourceFiles as $sourceFile) {
    if (file_exists($sourceFile)) {
        $sourceCode = file_get_contents($sourceFile);
        $name = basename($sourceFile, '.java');
        $byteCodes[$name] = $sourceCode; // Simulate bytecode just by keeping the source code
    }
}

$outputDir = $directory ?: getcwd();
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

foreach ($byteCodes as $name => $code) {
    $byteCodeFile = "$outputDir/$name.class";
    file_put_contents($byteCodeFile, $code); // Writing source as bytecode for demonstration
    echo "$byteCodeFile\n";
}
--EXPECT--
