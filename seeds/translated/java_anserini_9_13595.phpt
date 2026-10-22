--TEST--
Translated seed (inferredbugs-java: anserini/9)
--FILE--
<?php

class IndexCollection {
    private $input;
    private $index;
    private $threads;
    private $counters;

    public function __construct($input, $index, $threads) {
        $this->input = $input;
        $this->index = $index;
        $this->threads = $threads;
        $this->counters = ['indexedDocuments' => 0, 'emptyDocuments' => 0, 'errors' => 0];
    }

    public function run() {
        $files = scandir($this->input);
        $numFiles = count($files);
        $fileHandles = [];
        $workers = [];

        for ($i = 0; $i < $this->threads; $i++) {
            $workers[] = new Thread(function() use ($fileHandles) {
                foreach ($fileHandles as $inputFile) {
                    $content = file_get_contents($inputFile);
                    if ($content === false) {
                        $this->counters['errors']++;
                        continue;
                    }
                    if (empty(trim($content))) {
                        $this->counters['emptyDocuments']++;
                        continue;
                    }
                    $this->counters['indexedDocuments']++;
                }
            });
        }

        foreach ($files as $file) {
            if (is_file($this->input . '/' . $file)) {
                $fileHandles[] = $this->input . '/' . $file;
            }
        }

        foreach ($workers as $worker) {
            $worker->start();
        }
        foreach ($workers as $worker) {
            $worker->join();
        }

        echo "Indexed documents: " . $this->counters['indexedDocuments'] . "\n";
        echo "Empty documents: " . $this->counters['emptyDocuments'] . "\n";
        echo "Errors: " . $this->counters['errors'] . "\n";
    }
}

$input = $argv[1] ?? 'path/to/input';
$index = $argv[2] ?? 'path/to/index';
$threads = $argv[3] ?? 4;

$indexCollection = new IndexCollection($input, $index, $threads);
$indexCollection->run();
--EXPECT--
