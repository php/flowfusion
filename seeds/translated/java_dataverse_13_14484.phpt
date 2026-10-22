--TEST--
Translated seed (inferredbugs-java: dataverse/13)
--FILE--
<?php
$solrDocIdentifierDataverse = "dataverse_";
$groupPrefix = "group_";
$publicGroupId = 1;
$publicGroupString = $groupPrefix . "public";
$tmpNsaGroupId = 2;

function indexAll() {
    global $solrDocIdentifierDataverse, $publicGroupId, $publicGroupString, $tmpNsaGroupId, $groupPrefix;

    $groups = [
        $publicGroupId => $publicGroupString,
        $tmpNsaGroupId => "nsa",
        $tmpNsaGroupId + 1 => "flappybird",
        $tmpNsaGroupId + 2 => "2048"
    ];
    
    $groupIndexCount = 0;
    foreach ($groups as $key => $value) {
        $groupIndexCount++;
        echo "indexing group " . $groupIndexCount . " of " . count($groups) . ": indexed group " . $key . "\n";
    }

    $dataverses = range(1, 5); // Simulated data
    $dataverseIndexCount = 0;
    foreach ($dataverses as $dataverseId) {
        $dataverseIndexCount++;
        echo "indexing dataverse " . $dataverseIndexCount . " of " . count($dataverses) . ": indexed dataverse " . $dataverseId . "\n";
    }

    $datasets = range(1, 3); // Simulated data
    $datasetIndexCount = 0;
    foreach ($datasets as $datasetId) {
        $datasetIndexCount++;
        echo "indexing dataset " . $datasetIndexCount . " of " . count($datasets) . ": indexed dataset " . $datasetId . "\n";
    }

    echo "done iterating through all datasets\n";
}

indexAll();
--EXPECT--
