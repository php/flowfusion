--TEST--
Translated seed (inferredbugs-csharp: AsmResolver/8)
--FILE--
<?php

class BlobHeap {

    private $readBlobs = [];

    private $mainStream;

    public function __construct() {
        $this->mainStream = fopen('php://temp', 'r+');
    }

    public function reconstruct() {
        ftruncate($this->mainStream, 0);
        fwrite($this->mainStream, "\0");
        $this->readAllBlobs();

        foreach ($this->readBlobs as $index => $blob) {
            $length = count($blob);
            $this->writeCompressedUInt32($length);
            fwrite($this->mainStream, $blob);
        }
    }

    private function readAllBlobs() {
        fseek($this->mainStream, 1);
        while (ftell($this->mainStream) < filesize('php://temp')) {
            $alreadyExisted = array_key_exists(ftell($this->mainStream), $this->readBlobs);
            $value = $this->getBlob(ftell($this->mainStream));

            $length = count($value);
            if ($length == 0) break;
            if ($alreadyExisted) {
                fseek($this->mainStream, $length + $this->getCompressedUInt32Size($length), SEEK_CUR);
            }
        }
    }

    private function getBlob($index) {
        if (array_key_exists($index, $this->readBlobs)) {
            return $this->readBlobs[$index];
        }

        fseek($this->mainStream, $index);
        $length = $this->readCompressedUInt32();
        $bytes = fread($this->mainStream, $length);
        $this->readBlobs[$index] = $bytes;
        return $bytes;
    }

    private function writeCompressedUInt32($value) {
        fwrite($this->mainStream, pack('C*', ...array_map('ord', str_split($value))));
    }

    private function readCompressedUInt32() {
        return unpack('C', fread($this->mainStream, 1))[1];
    }

    private function getCompressedUInt32Size($value) {
        return strlen(pack('C*', ...array_map('ord', str_split($value))));
    }

    public function __destruct() {
        fclose($this->mainStream);
    }
}

$blobHeap = new BlobHeap();
$blobHeap->reconstruct();
--EXPECT--
