--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/12)
--FILE--
<?php

class JdbcColumnKey {
    private $name;
    private $index;

    public function __construct($name, $index) {
        $this->name = $name;
        $this->index = $index;
    }

    public function getName() {
        return $this->name;
    }
}

class FieldMapperColumnDefinition {
    private $name;
    private $getter;

    private function __construct($name, $getter) {
        $this->name = $name;
        $this->getter = $getter;
    }

    public static function renameDefinition($name) {
        return new self($name, null);
    }

    public static function customGetter($getter) {
        return new self(null, $getter);
    }

    public static function compose($renameDef, $customGetter) {
        return new self($renameDef->name, $customGetter->getter);
    }

    public function getName() {
        return $this->name;
    }

    public function getCustomGetter() {
        return $this->getter;
    }

    public function hasCustomSource() {
        return $this->getter !== null;
    }

    public function getCustomSourceReturnType() {
        return 'integer';
    }
}

$getter = function() {
    return 3;
};

$compose = FieldMapperColumnDefinition::compose(
    FieldMapperColumnDefinition::renameDefinition("blop"),
    FieldMapperColumnDefinition::customGetter($getter)
);

$compose->getName() == "blop";
$compose->getCustomGetter()() == 3;
$compose->hasCustomSource();
$compose->getCustomSourceReturnType() == 'integer';
--EXPECT--
