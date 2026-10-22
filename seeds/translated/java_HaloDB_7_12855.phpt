--TEST--
Translated seed (inferredbugs-java: HaloDB/7)
--FILE--
<?php

class DBMetaData {

    const META_DATA_SIZE = 9; // 1 byte for boolean + 8 bytes for long

    private $open = false;
    private $sequenceNumber = 0;

    private $dbDirectory;

    const METADATA_FILE_NAME = "META";

    public function __construct($dbDirectory) {
        $this->dbDirectory = $dbDirectory;
    }

    public function loadFromFile() {
        $metaFile = $this->dbDirectory . DIRECTORY_SEPARATOR . self::METADATA_FILE_NAME;
        if (file_exists($metaFile)) {
            $handle = fopen($metaFile, "rb");
            if ($handle) {
                $data = fread($handle, self::META_DATA_SIZE);
                fclose($handle);
                $this->open = ord($data[0]) !== 0;
                $this->sequenceNumber = unpack('P', substr($data, 1, 8))[1];
            }
        }
    }

    public function storeToFile() {
        $tempFileName = self::METADATA_FILE_NAME . ".temp";
        $tempFile = $this->dbDirectory . DIRECTORY_SEPARATOR . $tempFileName;
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        $handle = fopen($tempFile, "wb");
        if ($handle) {
            $data = chr($this->open ? 0xFF : 0) . pack('P', $this->sequenceNumber);
            fwrite($handle, $data);
            fclose($handle);
            rename($tempFile, $this->dbDirectory . DIRECTORY_SEPARATOR . self::METADATA_FILE_NAME);
        }
    }

    public function isOpen() {
        return $this->open;
    }

    public function setOpen($open) {
        $this->open = $open;
    }

    public function getSequenceNumber() {
        return $this->sequenceNumber;
    }

    public function setSequenceNumber($sequenceNumber) {
        $this->sequenceNumber = $sequenceNumber;
    }
}

function main() {
    $db = new DBMetaData(__DIR__);
    $db->setOpen(true);
    $db->setSequenceNumber(12345);
    $db->storeToFile();
    $db->setOpen(false);
    $db->loadFromFile();
}

main();
--EXPECT--
