--TEST--
Translated seed (inferredbugs-java: closure-compiler/36)
--FILE--
<?php

class CommandLineRunner {
    private $inputFiles;
    private $outputFile;
    private $options;

    public function __construct($inputFiles, $outputFile) {
        $this->inputFiles = $inputFiles;
        $this->outputFile = $outputFile;
        $this->options = [
            'debug' => true,
            'skipOutputs' => false,
        ];
    }

    public function run() {
        if ($this->options['skipOutputs']) {
            return;
        }

        $result = $this->compile();
        if ($result === false) {
            fwrite(STDERR, "Compilation failed.\n");
            exit(1);
        }

        file_put_contents($this->outputFile, $result);
    }

    private function compile() {
        $output = '';
        foreach ($this->inputFiles as $file) {
            if (!file_exists($file)) {
                return false;
            }
            $content = file_get_contents($file);
            $output .= $this->processContent($content);
        }
        return $output;
    }

    private function processContent($content) {
        // Simulate a simple process that can trigger an edge case
        return str_replace('var', 'let', $content);
    }
}

$inputFiles = ['input1.js', 'input2.js'];
$outputFile = 'output.js';

$runner = new CommandLineRunner($inputFiles, $outputFile);
$runner->run();
--EXPECT--
