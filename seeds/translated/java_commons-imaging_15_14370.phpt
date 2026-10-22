--TEST--
Translated seed (inferredbugs-java: commons-imaging/15)
--FILE--
<?php

class IconReader {
    private $width;
    private $height;
    private $iconCount;

    public function readIconFile($data) {
        $this->width = ord($data[18]);
        $this->height = ord($data[19]);
        $this->iconCount = unpack('v', substr($data, 4, 2))[1];

        if ($this->iconCount < 1 || $this->width < 1 || $this->height < 1) {
            throw new Exception("Invalid ICO file");
        }
    }

    public function getIconCount() {
        return $this->iconCount;
    }
}

$data = file_get_contents('path/to/icon.ico');
$reader = new IconReader();
try {
    $reader->readIconFile($data);
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
