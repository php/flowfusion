--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/12)
--FILE--
<?php

class CNode {
    public function asScalar() {
        return $this;
    }
}

class Scalar extends CNode {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

class Sequence extends CNode {
    private $items;

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function isEmpty() {
        return empty($this->items);
    }
}

class Mapping extends ArrayObject {
    private $source;

    public function getType() {
        return 'MAPPING';
    }

    public function asMapping() {
        return $this;
    }

    public function put($key, $value) {
        parent::offsetSet($key, new Scalar(strval($value)));
    }

    public function putIfNotNull($key, $node) {
        if ($node !== null) parent::offsetSet($key, $node);
    }

    public function putIfNotEmpty($key, $seq) {
        if (!$seq->isEmpty()) parent::offsetSet($key, $seq);
    }

    public function getScalarValue($key) {
        return $this->offsetGet($key)->asScalar()->getValue();
    }

    public function setSource($source) {
        $this->source = $source;
    }

    public function getSource() {
        return $this->source;
    }
}

$mapping = new Mapping();
$mapping->put("testKey", 123);
$mapping->put("anotherKey", true);
$sequence = new Sequence(["item1", "item2"]);
$mapping->putIfNotEmpty("sequenceKey", $sequence);
$scalarValue = $mapping->getScalarValue("testKey");
echo $scalarValue;
--EXPECT--
