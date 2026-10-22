--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/5)
--FILE--
<?php

class ExtensionObject {
    private $object;
    private $typeId;
    private $encodeType;
    
    const ENCODE_TYPE_BINARY = 'Binary';
    
    public function __construct($typeId, $object = null) {
        if ($typeId === null) {
            throw new InvalidArgumentException("typeId argument must not be null");
        }
        $this->typeId = $typeId;
        $this->object = $object;
        $this->encodeType = $object === null ? null : self::ENCODE_TYPE_BINARY;
    }
    
    public static function binaryEncode($encodeable, $maxSize = PHP_INT_MAX) {
        $size = min(strlen(serialize($encodeable)), $maxSize);
        $data = substr(serialize($encodeable), 0, $size);
        return new ExtensionObject('BinaryTypeId', $data);
    }
    
    public function getObject() {
        return $this->object;
    }

    public function getTypeId() {
        return $this->typeId;
    }
}

class Structure {
    public function __serialize() {
        return ["data" => "sample data"];
    }
}

$structure = new Structure();
$extensionObject = ExtensionObject::binaryEncode($structure);
echo $extensionObject->getTypeId();
?>
--EXPECT--
