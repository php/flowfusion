--TEST--
Translated seed (inferredbugs-java: CFLint/5)
--FILE--
<?php

class CFLint {
    private $bugs = [];
    
    public function scan($folder) {
        $files = scandir($folder);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = "$folder/$file";
                if (is_dir($filePath)) {
                    $this->scan($filePath);
                } elseif ($this->checkExtension($file)) {
                    $src = $this->load($filePath);
                    try {
                        $this->process($src, $filePath);
                    } catch (Exception $e) {
                        echo "Error processing $filePath: " . $e->getMessage() . PHP_EOL;
                    }
                }
            }
        }
    }

    private function checkExtension($file) {
        $allowedExtensions = ['.cfc', '.cfm'];
        foreach ($allowedExtensions as $ext) {
            if (str_ends_with($file, $ext)) {
                return true;
            }
        }
        return false;
    }

    private function load($file) {
        return file_get_contents($file);
    }

    private function process($src, $filename) {
        if (empty($src)) {
            throw new Exception("File is empty: $filename");
        }

        // Simulate a parsing process with a simple edge case
        if (preg_match('/(.+?)([;|,|\s]+)/', trim($src), $matches)) {
            $this->bugs[] = "Parsing issue in $filename: " . $matches[1];
        }

        // More processing could go here...
    }

    public function getBugs() {
        return $this->bugs;
    }
}

$scanner = new CFLint();
$scanner->scan('path/to/your/directory');
$bugs = $scanner->getBugs();

foreach ($bugs as $bug) {
    echo $bug . PHP_EOL;
}
--EXPECT--
