--TEST--
Translated seed (inferredbugs-java: commons-imaging/2)
--FILE--
<?php

class ImageContents
{
    public $header;
    public $ColorModeDataLength;
    public $ImageResourcesLength;
    public $LayerAndMaskDataLength;
    public $Compression;

    public function __construct($header, $ColorModeDataLength, $ImageResourcesLength, $LayerAndMaskDataLength, $Compression)
    {
        $this->header = $header;
        $this->ColorModeDataLength = $ColorModeDataLength;
        $this->ImageResourcesLength = $ImageResourcesLength;
        $this->LayerAndMaskDataLength = $LayerAndMaskDataLength;
        $this->Compression = $Compression;
    }

    public function dump()
    {
        echo "\nImageContents\n";
        echo "Compression: ".$this->Compression." (".dechex($this->Compression).")\n";
        echo "ColorModeDataLength: ".$this->ColorModeDataLength." (".dechex($this->ColorModeDataLength).")\n";
        echo "ImageResourcesLength: ".$this->ImageResourcesLength." (".dechex($this->ImageResourcesLength).")\n";
        echo "LayerAndMaskDataLength: ".$this->LayerAndMaskDataLength." (".dechex($this->LayerAndMaskDataLength).")\n\n";
    }
}

$header = null; // Simulating the header as null for demonstration
$imageContent = new ImageContents($header, 1000, 2000, 3000, 4000);
$imageContent->dump();

?>
--EXPECT--
