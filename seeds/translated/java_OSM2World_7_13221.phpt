--TEST--
Translated seed (inferredbugs-java: OSM2World/7)
--FILE--
<?php

class OSMFileReader {

    private $file;

    public function __construct($file) {
        if (!file_exists($file)) {
            throw new Exception("File not found: $file");
        }
        $this->file = $file;
    }

    public function getData() {
        $osmData = null;
        $useJOSMReader = false;

        if ($this->isJOSMGenerated($this->file)) {
            $useJOSMReader = true;
        } else {
            try {
                $osmData = $this->strictOSMFileReader();
            } catch (Exception $e) {
                $useJOSMReader = true;
            }
        }

        if ($useJOSMReader) {
            try {
                $osmData = $this->josmFileReader();
            } catch (Exception $e2) {
                throw new Exception("could not read OSM file (not even with workaround for JOSM files)", 0, $e2);
            }
        }

        return $osmData;
    }

    private function isJOSMGenerated($file) {
        $handle = fopen($file, "r");
        for ($i = 0; $i < 100; $i++) {
            $line = fgets($handle);
            if ($line === false) break;
            if (strpos($line, "generator='JOSM'") !== false) {
                fclose($handle);
                return true;
            }
        }
        fclose($handle);
        return false;
    }

    private function strictOSMFileReader() {
        // Simulating data retrieval
        return "Data from Strict OSM File Reader";
    }

    private function josmFileReader() {
        // Simulating data retrieval
        return "Data from JOSM File Reader";
    }
}

$file = 'path/to/osm/file.osm';
$reader = new OSMFileReader($file);
$data = $reader->getData();
echo $data;
--EXPECT--
