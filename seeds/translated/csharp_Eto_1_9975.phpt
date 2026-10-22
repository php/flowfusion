--TEST--
Translated seed (inferredbugs-csharp: Eto/1)
--FILE--
<?php

class IconHandler {
    private $control;

    public function __construct($image = null) {
        if ($image) {
            $this->control = $image;
        }
    }

    public function createFromStream($stream) {
        $data = stream_get_contents($stream);
        $this->control = $this->createImageFromData($data);
    }

    public function createFromFile($fileName) {
        $this->control = $this->createImageFromFile($fileName);
    }

    private function createImageFromData($data) {
        // Simulating image creation from raw data
        return $data; // In real scenario, it would be an image object
    }

    private function createImageFromFile($fileName) {
        // Simulating image creation from file
        return file_get_contents($fileName); // In real scenario, it would be an image object
    }

    public function getSize() {
        // Simulating size retrieval
        return strlen($this->control); // In real scenario, it would be image dimensions
    }
}

function main() {
    $iconHandler = new IconHandler();
    
    // Simulating stream input
    $stream = fopen('php://temp', 'r+');
    fwrite($stream, 'dummy_image_data');
    rewind($stream);

    $iconHandler->createFromStream($stream);
    fclose($stream);
    
    $iconHandler->createFromFile('dummy_file.txt');
    
    echo "Image size: " . $iconHandler->getSize() . PHP_EOL;
}

main();
--EXPECT--
