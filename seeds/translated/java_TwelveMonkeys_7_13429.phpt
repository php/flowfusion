--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/7)
--FILE--
<?php

class TIFFImageWriter {
    private $compression;
    private $output;
    private $image;

    public function setOutput($output) {
        $this->output = $output;
    }

    public function write($image) {
        $this->image = $image;
        $this->writeHeader();
        $this->writeImageData();
        fclose($this->output);
    }

    private function writeHeader() {
        // Simplified TIFF header writing
        fwrite($this->output, "II", 2); // TIFF Mark
        fwrite($this->output, pack("v", 42), 2); // Version number
        fwrite($this->output, pack("V", 8), 4); // Offset to IFD
    }

    private function writeImageData() {
        // Writing image dimensions as an example
        $width = imagesx($this->image);
        $height = imagesy($this->image);
        $data = pack("v", $width) . pack("v", $height);
        fwrite($this->output, $data);

        // Dummy image data
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($this->image, $x, $y);
                $r = ($color >> 16) & 0xFF;
                $g = ($color >> 8) & 0xFF;
                $b = $color & 0xFF;
                fwrite($this->output, pack("C*",$r, $g, $b), 3);
            }
        }
    }
}

function main() {
    $width = 100;
    $height = 100;
    $image = imagecreatetruecolor($width, $height);
    $color = imagecolorallocate($image, 255, 0, 0); // Red
    imagefilledrectangle($image, 0, 0, $width, $height, $color);

    $output = fopen('output.tiff', 'wb');
    $writer = new TIFFImageWriter();
    $writer->setOutput($output);
    $writer->write($image);

    imagedestroy($image);
}

main();
--EXPECT--
