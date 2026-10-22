--TEST--
Translated seed (inferredbugs-java: biweekly/16)
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
        return ($geo->getLatitude() !== null ? $geo->getLatitude() : '') . ';' .
               ($geo->getLongitude() !== null ? $geo->getLongitude() : '');
    }

    public function parseText($value) {
        $parts = explode(';', $value);
        if (count($parts) < 2) {
            throw new Exception("Cannot parse Geo, invalid format");
        }
        return new Geo(
            isset($parts[0]) && is_numeric($parts[0]) ? (float)$parts[0] : null,
            isset($parts[1]) && is_numeric($parts[1]) ? (float)$parts[1] : null
        );
    }
}

$marshaller = new GeoMarshaller();

// Test writing text
$prop = new Geo(12.34, 56.78);
echo $marshaller->writeText($prop) . "\n"; // Outputs: 12.34;56.78

// Test parsing text
$result = $marshaller->parseText("12.34;56.78");
echo $result->getLatitude() . ", " . $result->getLongitude() . "\n"; // Outputs: 12.34, 56.78

// Test parsing with missing values
try {
    $marshaller->parseText("12.34");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Outputs: Cannot parse Geo, invalid format
}

try {
    $marshaller->parseText("");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Outputs: Cannot parse Geo, invalid format
}

try {
    $marshaller->parseText("bad;56.78");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Outputs: Cannot parse Geo, invalid format
}

try {
    $marshaller->parseText("12.34;bad");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Outputs: Cannot parse Geo, invalid format
}
--EXPECT--
