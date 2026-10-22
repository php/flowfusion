--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/2)
--FILE--
<?php

class EXIFThumbnailReader {
    private $ifd;
    private $compression;

    public function __construct($ifd) {
        $this->ifd = $ifd;
        $this->compression = isset($ifd['compression']) ? $ifd['compression'] : 6;
    }

    public function read() {
        if ($this->compression == 1) {
            return $this->readUncompressed();
        } elseif ($this->compression == 6) {
            return $this->readJPEG();
        } else {
            throw new Exception("Unsupported EXIF thumbnail compression: " . $this->compression);
        }
    }

    private function readJPEG() {
        $jpegOffset = $this->ifd['jpeg_offset'] ?? null;
        if ($jpegOffset !== null) {
            // Simulate reading from the stream
            $input = fopen('php://temp', 'r+');
            fwrite($input, 'FAKE JPEG DATA');
            rewind($input);
            return $this->readJPEGThumbnail($input);
        }
        return null;
    }

    private function readJPEGThumbnail($input) {
        // Simulate processing the JPEG data
        return "JPEG Thumbnail Data";
    }

    private function readUncompressed() {
        $width = $this->ifd['width'] ?? null;
        $height = $this->ifd['height'] ?? null;

        if ($width === null || $height === null) {
            throw new Exception("Missing dimensions for RAW EXIF thumbnail");
        }

        $thumbData = str_repeat('RGB', $width * $height); // Simulating raw RGB data
        return $this->processRawThumbnail($thumbData);
    }

    private function processRawThumbnail($thumbData) {
        return "Processed Thumbnail Data";
    }
}

$ifd = [
    'compression' => 1,
    'width' => 100,
    'height' => 100,
    'jpeg_offset' => null
];

$reader = new EXIFThumbnailReader($ifd);
$thumbnail = $reader->read();
echo $thumbnail;

$ifd['compression'] = 6;
$reader = new EXIFThumbnailReader($ifd);
$thumbnail = $reader->read();
echo $thumbnail;
--EXPECT--
