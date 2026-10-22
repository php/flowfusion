--TEST--
Translated seed (inferredbugs-java: biweekly/13)
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
        return ($geo->getLatitude() ?? '') . ";" . ($geo->getLongitude() ?? '');
    }

    public function parseText($value) {
        $parts = explode(";", $value);
        if (count($parts) < 2) {
            throw new Exception("Cannot parse");
        }
        return new Geo(
            $parts[0] !== '' ? (float)$parts[0] : null,
            $parts[1] !== '' ? (float)$parts[1] : null
        );
    }
}

$marshaller = new GeoMarshaller();

$prop = new Geo(12.34, 56.78);
echo $marshaller->writeText($prop) . "\n";

$prop = new Geo(null, 56.78);
echo $marshaller->writeText($prop) . "\n";

$prop = new Geo(12.34, null);
echo $marshaller->writeText($prop) . "\n";

$prop = new Geo(null, null);
echo $marshaller->writeText($prop) . "\n";

$result = $marshaller->parseText("12.34;56.78");
echo $result->getLatitude() . ", " . $result->getLongitude() . "\n";

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
