--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/31)
--FILE--
<?php

class SingleChronicleQueueBuilder {
    private $path;

    private function __construct($path) {
        $this->path = $path;
    }

    public static function binary($path) {
        if (pathinfo($path, PATHINFO_EXTENSION) !== "bin") {
            throw new InvalidArgumentException("Invalid file extension");
        }
        return new self($path);
    }
}

function tempDir($name) {
    $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    return $path;
}

function main() {
    $queuePath = tempDir('SingleChronicleQueueBuilderTest');
    try {
        $queue = SingleChronicleQueueBuilder::binary($queuePath . DIRECTORY_SEPARATOR . 'queue.bin');
    } catch (Exception $e) {
        // Handle exception
    }
  
    try {
        $tempFile = tempnam(sys_get_temp_dir(), 'SingleChronicleQueueBuilderTest') . '.txt';
        try {
            $queue = SingleChronicleQueueBuilder::binary($tempFile);
        } catch (InvalidArgumentException $e) {
            // Exception is expected, do nothing
        }
    } finally {
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}

main();
--EXPECT--
