--TEST--
Translated seed (inferredbugs-java: biweekly/17)
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
        $lon = $geo->getLongitude();
        return ($lat !== null ? $lat : '') . ';' . ($lon !== null ? $lon : '');
    }

    public function parseText($value) {
        $parts = explode(';', $value);
        if (count($parts) !== 2) {
            throw new Exception("Cannot parse");
        }
        return new Geo(empty($parts[0]) ? null : (float)$parts[0], empty($parts[1]) ? null : (float)$parts[1]);
    }
}

$marshaller = new GeoMarshaller();

$prop1 = new Geo(12.34, 56.78);
echo $marshaller->writeText($prop1) . "\n"; // 12.34;56.78

$prop2 = new Geo(null, 56.78);
echo $marshaller->writeText($prop2) . "\n"; // ;56.78

$prop3 = new Geo(12.34, null);
echo $marshaller->writeText($prop3) . "\n"; // 12.34;

$prop4 = new Geo(null, null);
echo $marshaller->writeText($prop4) . "\n"; // ;

$result = $marshaller->parseText("12.34;56.78");
echo $result->getLatitude() . "\n"; // 12.34
echo $result->getLongitude() . "\n"; // 56.78

try {
    $marshaller->parseText("12.34");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Cannot parse
}

try {
    $marshaller->parseText("");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Cannot parse
}

try {
    $marshaller->parseText("bad;56.78");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Cannot parse
}

try {
    $marshaller->parseText("12.34;bad");
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; // Cannot parse
}
--EXPECT--
