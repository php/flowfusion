--TEST--
Translated seed (inferredbugs-java: commons-imaging/30)
--FILE--
<?php

class ByteSourceFile {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function getInputStream() {
        return fopen($this->file, 'rb');
    }

    public function getBlock($start, $length) {
        if ($start < 0 || $length < 0 || $start + $length < 0 || $start + $length > filesize($this->file)) {
            throw new Exception("Could not read block (block start: $start, block length: $length, data length: " . filesize($this->file) . ").");
        }

        $handle = fopen($this->file, 'rb');
        fseek($handle, $start);
        $data = fread($handle, $length);
        fclose($handle);
        return $data;
    }

    public function getLength() {
        return filesize($this->file);
    }

    public function getAll() {
        $handle = fopen($this->file, 'rb');
        $data = fread($handle, filesize($this->file));
        fclose($handle);
        return $data;
    }

    public function getDescription() {
        return "File: '" . realpath($this->file) . "'";
    }
}

$file = 'path/to/your/file'; // Change to an appropriate file path
$byteSource = new ByteSourceFile($file);
$byteSource->getBlock(0, 10);
$byteSource->getAll();
$byteSource->getLength();
$byteSource->getDescription();
--EXPECT--
