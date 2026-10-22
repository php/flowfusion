--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/5)
--FILE--
<?php

class TIFFImageWriter {
    private $compressionTypes = [0, 1, 2]; // Simulated compression types
    private $outputBuffer;

    public function setOutput($output) {
        $this->outputBuffer = $output;
    }

    public function write($image) {
        $compression = rand(0, 2); // Simulate random compression
        $this->setCompression($compression);

        // Simulate writing image data based on compression type
        switch ($compression) {
            case 0:
                $this->writeUncompressed($image);
                break;
            case 1:
                $this->writePackBits($image);
                break;
            case 2:
                $this->writeDeflate($image);
                break;
        }
    }

    private function setCompression($compression) {
        // Set the compression for the output
    }

    private function writeUncompressed($image) {
        // Simulate writing the image data uncompressed
        file_put_contents($this->outputBuffer, "Uncompressed: " . json_encode($image));
    }

    private function writePackBits($image) {
        // Simulate writing the image data with PackBits compression
        file_put_contents($this->outputBuffer, "PackBits: " . json_encode($image));
    }

    private function writeDeflate($image) {
        // Simulate writing the image data with Deflate compression
        file_put_contents($this->outputBuffer, "Deflate: " . json_encode($image));
    }
}

function main() {
    $filename = tempnam(sys_get_temp_dir(), 'tif');
    $writer = new TIFFImageWriter();
    $writer->setOutput($filename);
    
    // Create a sample image array
    $image = [
        'width' => 100,
        'height' => 100,
        'data' => array_fill(0, 10000, 255) // Sample image data
    ];
    
    $writer->write($image);
    echo "Output written to: $filename\n";
}

main();
--EXPECT--
