--TEST--
Translated seed (inferredbugs-java: biweekly/25)
--FILE--
<?php

class TextProperty {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

class TextPropertyMarshaller {
    private $dataType;

    public function __construct($dataType = "TEXT") {
        $this->dataType = $dataType;
    }

    public function writeText($prop) {
        return str_replace(";", "\\;", $prop->getValue());
    }

    public function parseText($value) {
        return new TextProperty(str_replace("\\;", ";", $value));
    }

    public function writeXml($prop) {
        $value = htmlspecialchars($prop->getValue());
        return "<{$this->dataType}>{$value}</{$this->dataType}>";
    }

    public function parseXml($xml) {
        preg_match("/<{$this->dataType}>(.*?)<\/{$this->dataType}>/", $xml, $matches);
        return new TextProperty($matches[1]);
    }
}

$marshaller = new TextPropertyMarshaller();
$prop = new TextProperty("the;text");
echo $marshaller->writeText($prop) . "\n"; // Outputs: the\;text

$propNull = new TextProperty(null);
echo $marshaller->writeText($propNull) . "\n"; // Outputs: 

$parsedProp = $marshaller->parseText("the\\;text");
echo $parsedProp->getValue() . "\n"; // Outputs: the;text

$xmlOutput = $marshaller->writeXml(new TextProperty("text"));
echo $xmlOutput . "\n"; // Outputs: <TEXT>text</TEXT>

$parsedXml = $marshaller->parseXml("<TEXT>text</TEXT>");
echo $parsedXml->getValue() . "\n"; // Outputs: text
--EXPECT--
