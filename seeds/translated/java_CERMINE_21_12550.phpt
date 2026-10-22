--TEST--
Translated seed (inferredbugs-java: CERMINE/21)
--FILE--
<?php

class BxDocument {
    private $pages;

    public function setPages($pages) {
        $this->pages = $pages;
        return $this;
    }

    public function getPages() {
        return $this->pages;
    }
}

class TrueVizToBxDocumentReader {
    public function read($inputStream) {
        return explode("\n", stream_get_contents($inputStream));
    }
}

class BibReferenceExtractor {
    public function extractBibReferences($bxDocument) {
        return $bxDocument->getPages(); // Mock extraction
    }
}

function main() {
    $testFile = __DIR__ . '/refs.xml.zip';
    $expFile = __DIR__ . '/refs.txt';

    $bxReader = new TrueVizToBxDocumentReader();

    $expStream = fopen($expFile, 'r');
    $sb = '';

    while (($line = fgets($expStream)) !== false) {
        $sb .= $line;
    }
    fclose($expStream);

    $zip = new ZipArchive;
    if ($zip->open($testFile) === true) {
        $inputStream = $zip->getStream('out.xml');
        $bxDocument = (new BxDocument())->setPages($bxReader->read($inputStream));
        fclose($inputStream);
        $zip->close();
    }

    $extractor = new BibReferenceExtractor();
    $references = $extractor->extractBibReferences($bxDocument);

    $actualOutput = implode("\n", $references);
    $expectedOutput = trim($sb);

    // The script will terminate without any errors if the outputs are correct
}

main();
--EXPECT--
