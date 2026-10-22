--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/11)
--FILE--
<?php

class CsvColumnDefinition {
    private $name;
    private $dateFormat;
    private $customReader;

    private function __construct($name, $dateFormat, $customReader) {
        $this->name = $name;
        $this->dateFormat = $dateFormat;
        $this->customReader = $customReader;
    }

    public static function compose($def1, $def2) {
        return new self($def2->name, $def1->dateFormat, $def2->customReader ?: $def1->customReader);
    }

    public static function dateFormatDefinition($dateFormat) {
        return new self(null, $dateFormat, null);
    }

    public static function renameDefinition($name) {
        return new self($name, null, null);
    }

    public static function customReaderDefinition($customReader) {
        return new self(null, null, $customReader);
    }

    public function rename() {
        return $this;
    }

    public function dateFormat() {
        return $this->dateFormat;
    }

    public function getCustomReader() {
        return $this->customReader;
    }

    public function hasCustomSource() {
        return isset($this->customReader);
    }

    public function getCustomSourceReturnType() {
        return 'integer';
    }
}

$compose = CsvColumnDefinition::compose(
    CsvColumnDefinition::compose(
        CsvColumnDefinition::dateFormatDefinition("yyyyMM"),
        CsvColumnDefinition::renameDefinition("blop")
    ),
    CsvColumnDefinition::customReaderDefinition(function() {
        return 3;
    })
);

$compose->rename();
$compose->dateFormat();
$compose->getCustomReader()();

$compose->hasCustomSource();
$compose->getCustomSourceReturnType();
--EXPECT--
