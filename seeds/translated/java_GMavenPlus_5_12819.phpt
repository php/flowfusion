--TEST--
Translated seed (inferredbugs-java: GMavenPlus/5)
--FILE--
<?php

class ExecuteMojo {
    public $scripts;
    public $sourceEncoding;

    public function execute() {
        foreach ($this->scripts as $script) {
            eval($script);
        }
    }
}

function createTempFile($content) {
    $file = tempnam(sys_get_temp_dir(), 'testFile_');
    file_put_contents($file, $content);
    return $file;
}

$executeMojo = new ExecuteMojo();

// Test Script String
$filePath = createTempFile("hello world");
$executeMojo->scripts = ["file_put_contents('$filePath', 'hello world');"];
$executeMojo->execute();
readfile($filePath);
unlink($filePath);

// Test Script Path
$executeMojo->sourceEncoding = "UTF-8";
$filePath = "testFile.txt";
$line = "Hello world!";
$executeMojo->scripts = ["file_put_contents('$filePath', '$line');"];
$executeMojo->execute();
echo file_get_contents($filePath);
unlink($filePath);
--EXPECT--
