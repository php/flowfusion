--TEST--
Translated seed (inferredbugs-java: biweekly/12)
--FILE--
<?php

class Geo {
    private $latitude;
    private $longitude;

    public function __construct($latitude, $longitude) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }
}

class GeoMarshaller {
    public function writeText($geo) {
        $lat = $geo->getLatitude();
        $long = $geo->getLongitude();
        return ($lat !== null ? $lat : '') . ';' . ($long !== null ? $long : '');
    }

    public function parseText($value) {
        if (empty($value)) {
            throw new Exception('CannotParseException');
        }
        $parts = explode(';', $value);
        if (count($parts) !== 2) {
            throw new Exception('CannotParseException');
        }
        $lat = isset($parts[0]) && $parts[0] !== '' ? floatval($parts[0]) : null;
        $long = isset($parts[1]) && $parts[1] !== '' ? floatval($parts[1]) : null;
        return new Geo($lat, $long);
    }
}

$marshaller = new GeoMarshaller();

$prop1 = new Geo(12.34, 56.78);
echo $marshaller->writeText($prop1) . PHP_EOL;

$prop2 = new Geo(null, 56.78);
echo $marshaller->writeText($prop2) . PHP_EOL;

$prop3 = new Geo(12.34, null);
echo $marshaller->writeText($prop3) . PHP_EOL;

$prop4 = new Geo(null, null);
echo $marshaller->writeText($prop4) . PHP_EOL;

$result = $marshaller->parseText("12.34;56.78");
echo $result->getLatitude() . " " . $result->getLongitude() . PHP_EOL;

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
