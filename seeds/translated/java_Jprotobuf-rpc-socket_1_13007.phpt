--TEST--
Translated seed (inferredbugs-java: Jprotobuf-rpc-socket/1)
--FILE--
<?php

class RpcData {
    private $data;
    private $attachment;

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getAttachment() {
        return $this->attachment;
    }

    public function setAttachment($attachment) {
        $this->attachment = $attachment;
    }
}

class MessageGeneratedRpcHandler {
    private $parseFromMethod;
    private $inputClass;

    public function __construct($inputClass) {
        $this->inputClass = $inputClass;
        if ($this->inputClass) {
            if (is_subclass_of($this->inputClass, 'GeneratedMessage')) {
                $this->parseFromMethod = 'parseFrom';
            }
        }
    }

    public function doHandle($data) {
        $input = null;
        $param = [];
        if ($data->getData() !== null && $this->parseFromMethod) {
            $input = call_user_func([$this->inputClass, $this->parseFromMethod], fopen('php://temp', 'r+'));
            $param[] = $input;
        }

        $retData = new RpcData();
        $ret = null; // Simulated service call

        if ($ret === null) {
            return $retData;
        }

        if ($ret !== null && $ret instanceof GeneratedMessage) {
            $response = $input->toByteArray();
            $retData->setData($response);
        }

        return $retData;
    }
}

class GeneratedMessage {
    public static function parseFrom($stream) {
        return new self();
    }

    public function toByteArray() {
        return "response data";
    }
}

$data = new RpcData();
$data->setData("test data");
$handler = new MessageGeneratedRpcHandler('GeneratedMessage');
$handler->doHandle($data);
--EXPECT--
