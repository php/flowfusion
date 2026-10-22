--TEST--
Translated seed (inferredbugs-java: dataverse/16)
--FILE--
<?php

class Dataset {
    public $id;
    public $ownerId;
    public $files = [];
    public $releasedVersion;
    public $versionNumber;

    public function __construct($id, $ownerId) {
        $this->id = $id;
        $this->ownerId = $ownerId;
    }

    public function getLatestVersion() {
        return $this->releasedVersion;
    }
}

class DataFile {
    public $contentType;
    public $fileSystemName;

    public function __construct($contentType) {
        $this->contentType = $contentType;
    }
}

class DatasetVersion {
    public $fileMetadatas = [];
    public $versionNumber;
}

function simulateDatasetCreation() {
    $dataset = new Dataset(1, 101);
    $dataset->releasedVersion = new DatasetVersion();
    $dataset->releasedVersion->versionNumber = 1;

    $newFiles = [
        new DataFile("application/pdf"),
        new DataFile("application/pdf")
    ];

    $dataset->files = $newFiles;

    $nextMajorVersion = ($dataset->getLatestVersion()->versionNumber + 1) . ".0";
    $nextMinorVersion = $dataset->getLatestVersion()->versionNumber . ".1";

    // Simulate saving the dataset and printing version numbers
    echo "Next Major Version: $nextMajorVersion\n";
    echo "Next Minor Version: $nextMinorVersion\n";

    // Simulate file handling
    foreach ($newFiles as $file) {
        $file->fileSystemName = uniqid("file_") . ".pdf";
        echo "File system name generated: {$file->fileSystemName}\n";
    }
}

simulateDatasetCreation();
--EXPECT--
