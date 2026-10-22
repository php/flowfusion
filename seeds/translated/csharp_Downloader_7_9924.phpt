--TEST--
Translated seed (inferredbugs-csharp: Downloader/7)
--FILE--
<?php

class FileStorage {
    private $fileName;

    public function __construct($directory, $fileExtension = "") {
        $this->fileName = tempnam($directory, 'prefix_') . $fileExtension;
    }

    public function read() {
        return fopen($this->fileName, 'c+');
    }

    public function write($data, $offset, $count) {
        $writer = fopen($this->fileName, 'a');
        fseek($writer, $offset);
        fwrite($writer, substr($data, 0, $count));
        fclose($writer);
    }

    public function clear() {
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }
    }

    public function getLength() {
        return file_exists($this->fileName) ? filesize($this->fileName) : 0;
    }
}

$storage = new FileStorage('/tmp', '.txt');
$data = str_repeat('a', 1024);
$storage->write($data, 0, strlen($data));
$length = $storage->getLength();
$storage->clear();
--EXPECT--
