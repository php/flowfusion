--TEST--
Translated seed (inferredbugs-java: dataverse/4)
--FILE--
<?php
class Dataset {
    public $id;
    public $owner;
    public $versions = [];
    public function getFileSystemDirectory() {
        return 'dataset_directory/' . $this->id;
    }
}

class DatasetVersion {
    public $dataset;
    public $versionState;
    public $fileMetadatas = [];
    public function initDatasetFieldValues() {
        return [];
    }
}

class DataFile {
    public $name;
    public $contentType;
    public $owner;
    public function __construct($name, $contentType) {
        $this->name = $name;
        $this->contentType = $contentType;
    }
}

class FileMetadata {
    public $dataFile;
}

function ingestableAsTabular($dataFile) {
    return isset($dataFile->name) && substr($dataFile->name, -4) === '.dta';
}

$dataset = new Dataset();
$dataset->id = 1;
$dataset->owner = 'owner_id';
$datasetVersion = new DatasetVersion();
$datasetVersion->dataset = $dataset;
$datasetVersion->versionState = 'DRAFT';
$datasetVersion->fileMetadatas[] = new FileMetadata();
$dataset->versions[] = $datasetVersion;

$newFile = new DataFile('file.dta', 'application/octet-stream');

if (ingestableAsTabular($newFile)) {
    $directory = $dataset->getFileSystemDirectory();
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    $filePath = $directory . '/' . $newFile->name;
    file_put_contents($filePath, 'dummy data for file');
}
--EXPECT--
