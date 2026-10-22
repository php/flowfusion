--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/1)
--FILE--
<?php

class IFFImageWriter {
    const CHUNK_BODY = 1;
    const CHUNK_FORM = 2;
    const TYPE_ILBM = 3;

    private $output;

    public function setOutput($output) {
        $this->output = $output;
    }

    public function write($image) {
        $this->assertOutput();
        $imageData = $this->packImageData($image);
        $this->writeBody($imageData);
    }

    private function assertOutput() {
        if ($this->output === null) {
            throw new Exception("Output not set");
        }
    }

    private function writeBody($imageData) {
        $this->writeInt(self::CHUNK_BODY);
        $this->writeInt(strlen($imageData));
        fwrite($this->output, $imageData);
        if (strlen($imageData) % 2 === 0) {
            fwrite($this->output, "\0");
        }
        fflush($this->output);
    }

    private function packImageData($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        $imageData = '';
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $imageData .= pack('C', $color);
            }
        }
        return $imageData;
    }

    private function writeInt($value) {
        fwrite($this->output, pack('N', $value));
    }
}

$image = imagecreate(16, 16);
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, 15, 15, $black);
imagefilledellipse($image, 8, 8, 15, 15, $white);

$output = fopen('php://temp', 'w+');
$writer = new IFFImageWriter();
$writer->setOutput($output);
$writer->write($image);
fclose($output);
imagedestroy($image);
--EXPECT--
