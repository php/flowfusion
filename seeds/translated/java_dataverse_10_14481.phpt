--TEST--
Translated seed (inferredbugs-java: dataverse/10)
--FILE--
<?php
class Dataset {
    public $files = [];
    public $releasedVersion;
    public $editVersion;
    public $owner;
    public $id;

    public function getLatestVersion() {
        return $this->editVersion;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function getFileSystemDirectory() {
        return '/tmp/dataset_directory/'.$this->id;
    }
}

class DataFile {
    public $fileSystemName;
    public $fileSystemLocation;
    public $contentType;

    public function __construct($name, $type) {
        $this->fileSystemName = $name;
        $this->contentType = $type;
    }

    public function setIngestScheduled() {
        // Placeholder for actual ingestion logic
    }

    public function setIngestInProgress() {
        // Placeholder for actual ingestion logic
    }
}

function createDataset() {
    $dataset = new Dataset();
    $dataset->id = 1;
    $dataset->releasedVersion = (object)['versionNumber' => 1, 'minorVersionNumber' => 0];
    $dataset->editVersion = (object)['versionNumber' => 1, 'minorVersionNumber' => 0];
    return $dataset;
}

function saveDataset($dataset) {
    $dataset->editVersion->versionNumber++;
    $dataset->editVersion->minorVersionNumber = 0;

    if ($dataset->getFileSystemDirectory() && !file_exists($dataset->getFileSystemDirectory())) {
        mkdir($dataset->getFileSystemDirectory(), 0777, true);
    }

    $newFiles = [];
    for ($i = 0; $i < 3; $i++) {
        $dataFile = new DataFile('file'.$i.'.txt', 'text/plain');
        $dataFile->fileSystemLocation = $dataset->getFileSystemDirectory().'/'.$dataFile->fileSystemName;
        file_put_contents($dataFile->fileSystemLocation, 'Content of '.$dataFile->fileSystemName);
        $newFiles[] = $dataFile;
        $dataFile->setIngestScheduled();
    }

    foreach ($newFiles as $dFile) {
        $dFile->setIngestInProgress();
    }
}

$dataset = createDataset();
saveDataset($dataset);
?>
--EXPECT--
