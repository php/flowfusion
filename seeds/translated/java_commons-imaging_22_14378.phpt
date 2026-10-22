--TEST--
Translated seed (inferredbugs-java: commons-imaging/22)
--FILE--
<?php

class TiffOutputSet {
    private $gpsDirectory;

    public function __construct() {
        $this->gpsDirectory = new TiffOutputDirectory();
    }

    public function setGPSInDegrees($latitude, $longitude) {
        // Simulating setting GPS data and GPS version ID
        $this->gpsDirectory->setField(new TiffOutputField('GPS_VERSION_ID', '1.0.0'));
    }

    public function getGPSDirectory() {
        return $this->gpsDirectory;
    }
}

class TiffOutputDirectory {
    private $fields = [];

    public function setField($field) {
        $this->fields[] = $field;
    }

    public function getFields() {
        return $this->fields;
    }
}

class TiffOutputField {
    public $tagInfo;
    private $value;

    public function __construct($tagInfo, $value) {
        $this->tagInfo = $tagInfo;
        $this->value = $value;
    }

    public function bytesEqual($comparison) {
        return $this->value === $comparison;
    }
}

class GpsTagConstants {
    const GPS_TAG_GPS_VERSION_ID = 'GPS_VERSION_ID';
    const GPS_VERSION = '1.0.0';
}

$tiffOutputSet = new TiffOutputSet();
$tiffOutputSet->setGPSInDegrees(1.0, 1.0);
$gpsDirectory = $tiffOutputSet->getGPSDirectory();
$gpsVersionId = null;

foreach ($gpsDirectory->getFields() as $field) {
    if ($field->tagInfo === GpsTagConstants::GPS_TAG_GPS_VERSION_ID) {
        $gpsVersionId = $field;
        break;
    }
}

if ($gpsVersionId !== null && $gpsVersionId->bytesEqual(GpsTagConstants::GPS_VERSION)) {
    // Successful execution without errors
}
--EXPECT--
