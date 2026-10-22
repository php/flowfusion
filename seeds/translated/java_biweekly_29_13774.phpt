--TEST--
Translated seed (inferredbugs-java: biweekly/29)
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
    protected $className;

    public function __construct($className) {
        $this->className = $className;
    }

    public function writeText($property) {
        return str_replace(';', '\;', $property->getValue());
    }

    public function parseText($value) {
        return new $this->className(str_replace('\;', ';', $value));
    }

    public function writeXml($property) {
        return "<text>" . htmlspecialchars($property->getValue()) . "</text>";
    }

    public function parseXml($xml) {
        preg_match("/<text>(.*?)<\/text>/", $xml, $matches);
        return new $this->className($matches[1]);
    }
}

$marshaller = new TextPropertyMarshaller('TextProperty');

$prop1 = new TextProperty("the;text");
echo $marshaller->writeText($prop1) . "\n";

$prop2 = new TextProperty(null);
echo $marshaller->writeText($prop2) . "\n";

$parsedProp = $marshaller->parseText("the\\;text");
echo $parsedProp->getValue() . "\n";

$xmlOutput = $marshaller->writeXml(new TextProperty("text"));
echo $xmlOutput . "\n";

$parsedXmlProp = $marshaller->parseXml("<text>text</text>");
echo $parsedXmlProp->getValue() . "\n";

$parsedXmlData = $marshaller->parseXml("<text>mailto:johndoe@example.com</text>");
echo $parsedXmlData->getValue() . "\n";
--EXPECT--
