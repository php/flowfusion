--TEST--
Translated seed (inferredbugs-java: Java-Lang/9)
--FILE--
<?php

function newTemporaryFile($name) {
    $tmpDir = sys_get_temp_dir() . '/vmf/';
    if (!is_dir($tmpDir)) {
        mkdir($tmpDir);
    }
    $file = $tmpDir . $name;
    if (file_exists($file)) {
        unlink($file);
    }
    return $file;
}

class VanillaMappedFile {
    private $file;
    private $size;

    public function __construct($fileName) {
        $this->file = fopen($fileName, 'c+');
        $this->size = 0;
    }

    public function size() {
        return $this->size;
    }

    public function close() {
        fclose($this->file);
    }

    public function sliceOf($length) {
        $this->size = $length;
        return new VanillaMappedBuffer($this->file, $length);
    }
}

class VanillaMappedBuffer {
    private $file;
    private $length;

    public function __construct($file, $length) {
        $this->file = $file;
        $this->length = $length;
    }

    public function writeLong($offset, $value) {
        fseek($this->file, $offset);
        fwrite($this->file, pack('P', $value));
    }

    public function readLong($offset) {
        fseek($this->file, $offset);
        $data = fread($this->file, 8);
        return unpack('P', $data)[1];
    }

    public function size() {
        return $this->length;
    }
}

$file1 = newTemporaryFile("vmf-create-1");
$file2 = newTemporaryFile("vmf-create-2");

$vmf1 = new VanillaMappedFile($file1);
$vmf2 = new VanillaMappedFile($file2);

$buffer1 = $vmf1->sliceOf(128);
$buffer2 = $vmf2->sliceOf(256);

$buffer1->writeLong(0, 1);
$buffer2->writeLong(0, 2);

$vmf1->close();
$vmf2->close();
--EXPECT--
