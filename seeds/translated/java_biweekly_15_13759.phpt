--TEST--
Translated seed (inferredbugs-java: biweekly/15)
--FILE--
<?php

class Geo {
    public $latitude;
    public $longitude;

    public function __construct($latitude, $longitude) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}

class GeoMarshaller {
    public function writeText($prop) {
        return ($prop->latitude !== null ? $prop->latitude : '') . ';' . ($prop->longitude !== null ? $prop->longitude : '');
    }

    public function parseText($value) {
        $parts = explode(';', $value);
        if (count($parts) != 2) {
            throw new Exception("CannotParseException");
        }
        return new Geo(isset($parts[0]) && $parts[0] !== '' ? (float)$parts[0] : null, isset($parts[1]) && $parts[1] !== '' ? (float)$parts[1] : null);
    }
}

$marshaller = new GeoMarshaller();

$prop1 = new Geo(12.34, 56.78);
echo $marshaller->writeText($prop1) . "\n";

$prop2 = new Geo(null, 56.78);
echo $marshaller->writeText($prop2) . "\n";

$prop3 = new Geo(12.34, null);
echo $marshaller->writeText($prop3) . "\n";

$prop4 = new Geo(null, null);
echo $marshaller->writeText($prop4) . "\n";

$result1 = $marshaller->parseText("12.34;56.78");
echo $result1->latitude . "," . $result1->longitude . "\n";

try {
    $marshaller->parseText("12.34");
} catch (Exception $e) {}

try {
    $marshaller->parseText("");
} catch (Exception $e) {}

try {
    $marshaller->parseText("bad;56.78");
} catch (Exception $e) {}

try {
    $marshaller->parseText("12.34;bad");
} catch (Exception $e) {}
--EXPECT--
