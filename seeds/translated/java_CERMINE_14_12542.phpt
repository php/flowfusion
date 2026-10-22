--TEST--
Translated seed (inferredbugs-java: CERMINE/14)
--FILE--
<?php

class BxDocument {
    private $filename;
    private $pages = [];

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function setPages($pages) {
        $this->pages = $pages;
    }
}

class BxPage {
    private $parent;

    public function setParent($parent) {
        $this->parent = $parent;
    }
}

class TrueVizToBxDocumentReader {
    public function read($inputStream) {
        // Simulating reading XML content and returning pages.
        return [new BxPage(), new BxPage()];
    }
}

class ZipExtractor {
    private $zipFile;

    public function __construct($path) {
        $this->zipFile = new ZipArchive();
        $this->zipFile->open($path);
    }

    public function getDocuments() {
        $documents = [];
        $tvReader = new TrueVizToBxDocumentReader();
        for ($i = 0; $i < $this->zipFile->numFiles; $i++) {
            $zipEntryName = $this->zipFile->getNameIndex($i);
            if (substr($zipEntryName, -4) === '.xml') {
                $inputStream = $this->zipFile->getStream($zipEntryName);
                $pages = $tvReader->read($inputStream);
                $newDoc = new BxDocument();
                foreach ($pages as $page) {
                    $page->setParent($newDoc);
                }
                $newDoc->setFilename($zipEntryName);
                $newDoc->setPages($pages);
                $documents[] = $newDoc;
            }
        }
        return $documents;
    }
}

$zipExtractor = new ZipExtractor('path/to/your/file.zip');
$documents = $zipExtractor->getDocuments();
--EXPECT--
