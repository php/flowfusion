--TEST--
Translated seed (inferredbugs-java: dataverse/21)
--FILE--
<?php
$dataset = [
    'id' => null,
    'owner' => null,
    'versions' => [],
    'files' => [],
    'releaseVersion' => null,
];

function releaseDataset($dataset, $user) {
    if (is_null($dataset['id'])) {
        throw new RuntimeException("Dataset not found.");
    }

    $dataset['releaseVersion'] = !is_null($dataset['releaseVersion']) ? $dataset['releaseVersion'] + 1 : 1;

    // Simulate saving dataset (here you may want to add your own logic)
    return $dataset;
}

$ownerId = rand(1, 100);
$dataset['owner'] = $ownerId;
$dataset['id'] = rand(1, 10000);
$dataset['versions'][] = [
    'versionNumber' => 1,
    'isReleased' => false,
];

$editMode = 'CREATE';

if (is_null($dataset['id'])) {
    throw new RuntimeException("On Dataset page without id or ownerid.");
} else {
    if (!isset($dataset['releaseVersion'])) {
        $dataset['releaseVersion'] = 1;
    }

    for ($i = 0; $i < 10; $i++) {
        $dataset['files'][] = [
            'fileName' => "file".$i.".txt",
            'size' => rand(100, 1000)
        ];
    }

    $dataset = releaseDataset($dataset, $ownerId);
}

foreach ($dataset['files'] as $file) {
    echo "File uploaded: " . $file['fileName'] . " with size " . $file['size'] . PHP_EOL;
}

echo "Dataset ID: " . $dataset['id'] . " released at version: " . $dataset['releaseVersion'] . PHP_EOL;
--EXPECT--
