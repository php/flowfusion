--TEST--
Translated seed (inferredbugs-java: CERMINE/12)
--FILE--
<?php

class FileExtractor {

    private $inputStream;

    public function __construct($inputStream) {
        $this->inputStream = $inputStream;
    }

    public function getDocument() {
        $isr = fopen($this->inputStream, 'r');
        $reader = new TrueVizToBxDocumentReader();
        return (new BxDocument())->setPages($reader->read($isr));
    }

}

class TrueVizToBxDocumentReader {
    public function read($inputStream) {
        $content = stream_get_contents($inputStream);
        fclose($inputStream);
        return explode("\n", $content); // Simulate reading lines.
    }
}

class BxDocument {
    private $pages;

    public function setPages($pages) {
        $this->pages = $pages;
        return $this;
    }
}

$inputStream = 'php://temp';
file_put_contents($inputStream, "Page 1\nPage 2\nPage 3");
$fileExtractor = new FileExtractor($inputStream);
$fileExtractor->getDocument();

?>
--EXPECT--
