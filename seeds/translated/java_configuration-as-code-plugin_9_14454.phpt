--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/9)
--FILE--
<?php

class Attribute {
    private $name;
    private $type;
    private $multiple = false;
    private $preferredName;
    private $getter;
    private $setter;

    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->getter = [$this, 'getValue'];
        $this->setter = [$this, 'setValue'];
    }

    public function toString() {
        return sprintf("%s(class: %s, multiple: %s)", $this->name, $this->type, $this->multiple);
    }

    public function getName() {
        return $this->preferredName ?? $this->name;
    }

    public function isMultiple() {
        return $this->multiple;
    }

    public function multiple($multiple) {
        $this->multiple = $multiple;
        return $this;
    }

    public function preferredName($preferredName) {
        $this->preferredName = $preferredName;
        return $this;
    }

    public function setValue($target, $value) {
        if ($this->multiple && !is_array($value)) {
            throw new InvalidArgumentException("Value should be a list.");
        }
        $target->{$this->name} = $value;
    }

    public function getValue($target) {
        return $target->{$this->name} ?? null;
    }

    public static function main() {
        $object = new class {
            public $attr;
        };
        $attribute = new Attribute('attr', 'string');
        $attribute->multiple(true);
        $attribute->setValue($object, ['value1', 'value2']);
        echo $attribute->toString() . "\n";
        print_r($attribute->getValue($object));
    }
}

Attribute::main();
--EXPECT--
