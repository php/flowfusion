--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/1)
--FILE--
<?php

abstract class FdfsRequest {
    protected $head;
    protected $inputFile;

    public function getHead() {
        return $this->head;
    }

    public function getHeadByte($charset) {
        $this->head->setContentLength($this->getBodyLength($charset));
        return $this->head->toByte();
    }

    public function encodeParam($charset) {
        return FdfsParamMapper::toByte($this, $charset);
    }

    protected function getBodyLength($charset) {
        $objectMateData = FdfsParamMapper::getObjectMap(get_class($this));
        return $objectMateData->getFieldsSendTotalByteSize($this, $charset) + $this->getFileSize();
    }

    public function getInputFile() {
        return $this->inputFile;
    }

    public function getFileSize() {
        return 0;
    }
}

class ProtoHead {
    private $contentLength;

    public function setContentLength($length) {
        $this->contentLength = $length;
    }

    public function toByte() {
        return pack('N', $this->contentLength);
    }
}

class FdfsParamMapper {
    public static function toByte($request, $charset) {
        return b"ExampleParam";
    }

    public static function getObjectMap($class) {
        return new ObjectMateData();
    }
}

class ObjectMateData {
    public function getFieldsSendTotalByteSize($request, $charset) {
        return 100; // Simulated byte size for fields
    }
}

$instance = new class extends FdfsRequest {
    protected $head;

    public function __construct() {
        $this->head = new ProtoHead();
    }
};

$instance->getHeadByte('UTF-8');
$instance->encodeParam('UTF-8');
--EXPECT--
