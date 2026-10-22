--TEST--
Translated seed (inferredbugs-java: commons-imaging/23)
--FILE--
<?php

class TiffOutputSet {
    private $gpsDirectory;

    public function __construct() {
        $this->gpsDirectory = new TiffOutputDirectory();
    }

    public function setGPSInDegrees($lat, $lon) {
        // Simulating setting GPS coordinates
        $this->gpsDirectory->setGPSVersionId();
    }

    public function getGPSDirectory() {
        return $this->gpsDirectory;
    }
}

class TiffOutputDirectory {
    private $gpsVersionId;

    public function setGPSVersionId() {
        $this->gpsVersionId = GpsTagConstants::GPS_VERSION;
    }

    public function findField($tag) {
        if ($tag === GpsTagConstants::GPS_TAG_GPS_VERSION_ID) {
            return $this->gpsVersionId;
        }
        return null;
    }
}

class GpsTagConstants {
    const GPS_TAG_GPS_VERSION_ID = 'GPSVersionID';
    const GPS_VERSION = '2.2.0.0';
}

$tiffOutputSet = new TiffOutputSet();
$tiffOutputSet->setGPSInDegrees(1.0, 1.0);
$gpsDirectory = $tiffOutputSet->getGPSDirectory();
$gpsVersionId = $gpsDirectory->findField(GpsTagConstants::GPS_TAG_GPS_VERSION_ID);
$valid = $gpsVersionId !== null && $gpsVersionId === GpsTagConstants::GPS_VERSION;

?>
--EXPECT--
