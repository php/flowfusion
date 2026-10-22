--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/2)
--FILE--
<?php

class FileFinder {
    public static function main() {
        $textFiles = [];
        $dir = __DIR__ . "/src/test/php/com/test/site";
        
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (strpos($file, "Test") !== false) {
                    $textFiles[] = $file;
                }
            }
        }
    }
}

FileFinder::main();
--EXPECT--
