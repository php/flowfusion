--TEST--
Translated seed (inferredbugs-java: CERMINE/13)
--FILE--
<?php

class RecursiveDirExtractor
{
    protected $directory;

    public function __construct($path)
    {
        $this->directory = $path;
        if (!is_dir($this->directory)) {
            throw new RuntimeException("Source directory for documents doesn't exist: " . $path);
        }
    }

    public function getDocuments()
    {
        $documents = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->directory));

        foreach ($iterator as $file) {
            if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'xml') {
                $pages = $this->readXmlFile($file->getPathname());
                $doc = new BxDocument();
                $doc->setFilename($file->getFilename());
                $doc->setPages($pages);
                $documents[] = $doc;
            }
        }

        return $documents;
    }

    private function readXmlFile($filePath)
    {
        // Simulated reading of XML file and returning mock BxPage array
        return [new BxPage(), new BxPage()]; // Replace with actual XML reading logic
    }
}

class BxDocument
{
    private $filename;
    private $pages;

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function setPages($pages)
    {
        $this->pages = $pages;
    }
}

class BxPage
{
    // Simulate the BxPage class.
}

$extractor = new RecursiveDirExtractor('/path/to/directory');
$documents = $extractor->getDocuments();
--EXPECT--
