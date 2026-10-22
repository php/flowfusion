--TEST--
Translated seed (inferredbugs-java: dataverse/11)
--FILE--
<?php

class Dataset {
    public $id;
    public $versionNumber;
    public $minorVersionNumber;
    public $releasedVersion;
    public $fileMetadatas = [];
    public $files = [];
    
    public function getLatestVersion() {
        return $this;
    }

    public function setReleaseDate($date) {}
}

class DataFile {
    public $name;
    public $contentType;
    public $fileSystemLocation;
    public $isIngestScheduled = false;

    public function __construct($name, $contentType) {
        $this->name = $name;
        $this->contentType = $contentType;
    }

    public function setIngestScheduled() {
        $this->isIngestScheduled = true;
    }

    public function isIngestScheduled() {
        return $this->isIngestScheduled;
    }
}

class DatasetVersion {
    public $fileMetadatas = [];

    public function setFileMetadatas($metadatas) {
        $this->fileMetadatas = $metadatas;
    }
}

$dataset = new Dataset();
$dataset->id = 1;
$dataset->releasedVersion = (object) ['versionNumber' => 1, 'minorVersionNumber' => 0];
$dataset->versionNumber = $dataset->releasedVersion->versionNumber + 1;
$dataset->minorVersionNumber = 0;

$files = [
    new DataFile("file1.txt", "text/plain"),
    new DataFile("file2.csv", "text/csv"),
];

$dataset->files = $files;

foreach ($dataset->files as $dataFile) {
    $dataFile->setIngestScheduled();
}

foreach ($dataset->files as $dataFile) {
    if ($dataFile->isIngestScheduled()) {
        echo "Ingesting file: " . $dataFile->name . "\n";
        // Simulate file ingestion
        $dataFile->setIngestScheduled(false);
    }
}

$datasetVersion = new DatasetVersion();
$datasetVersion->setFileMetadatas($dataset->fileMetadatas);

echo "Dataset version updated: " . $dataset->versionNumber . "." . $dataset->minorVersionNumber . "\n";
--EXPECT--
