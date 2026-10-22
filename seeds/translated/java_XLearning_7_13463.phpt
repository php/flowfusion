--TEST--
Translated seed (inferredbugs-java: XLearning/7)
--FILE--
<?php

class XLearningContainer {

    private $conf;
    private $role;
    private $index;
    private $inputFileList;

    public function __construct() {
        $this->conf = parse_ini_file('config.ini'); // Simulating configuration load
        $this->role = $_ENV['XLEARNING_TF_ROLE'] ?? 'worker';
        $this->index = (int) ($_ENV['XLEARNING_TF_INDEX'] ?? 0);
    }

    private function prepareInputFiles() {
        $inputPaths = explode(",", $this->conf['input_paths']);
        
        foreach ($inputPaths as $path) {
            $downloadDst = '/tmp/' . basename($path);
            if (file_exists($downloadDst)) {
                unlink($downloadDst);
            }
            $success = copy($path, $downloadDst);
            if (!$success) {
                echo "Failed to download: $path\n";
                exit(1);
            }
        }
        $this->inputFileList = json_encode($inputPaths);
    }

    private function run() {
        if ($this->role === 'worker') {
            $this->prepareInputFiles();
        }

        // Simulate a process execution
        $command = $this->conf['exec_command'];
        $process = popen($command, 'r');

        if (is_resource($process)) {
            while (!feof($process)) {
                echo fgets($process);
            }
            pclose($process);
        }
    }

    public static function main() {
        $container = new XLearningContainer();
        try {
            $container->run();
            echo "Container finished successfully\n";
            exit(0);
        } catch (Exception $e) {
            echo "Container run failed: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
}

XLearningContainer::main();
--EXPECT--
