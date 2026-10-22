--TEST--
Translated seed (inferredbugs-java: android-maven-plugin/9)
--FILE--
<?php

$mergeManifests = false;
$genDirectory = __DIR__ . '/generated-sources/r';
$sourceManifestFile = __DIR__ . '/source/AndroidManifest.xml';
$androidManifestFile = $genDirectory . '/AndroidManifest.xml';
$resourcesDirectory = __DIR__ . '/resources';
$combinedAssets = __DIR__ . '/combinedAssets';
$extractedDependenciesJavaSources = __DIR__ . '/extractedDependenciesJavaSources';

if (!is_dir($genDirectory)) {
    mkdir($genDirectory, 0777, true);
}

function copyManifest($sourceManifestFile, $androidManifestFile) {
    if (!file_exists($sourceManifestFile)) {
        echo "Manifest copying disabled. Using default manifest only\n";
        return;
    }

    $doc = new DOMDocument();
    $doc->load($sourceManifestFile);
    $xml = $doc->saveXML();
    file_put_contents($androidManifestFile, $xml);
    echo "Manifest copied from $sourceManifestFile to $androidManifestFile\n";
}

function generateR($genDirectory) {
    echo "Generating R file at $genDirectory\n";
    if (!is_dir($genDirectory)) {
        mkdir($genDirectory, 0777, true);
    }
}

function mergeManifests($mergeManifests, $androidManifestFile) {
    if (!$mergeManifests) {
        echo "Manifest merging disabled. Using project manifest only\n";
        return;
    }
    // Simulating manifest merging logic without actual file handling for brevity
    echo "Merging manifests...\n";
}

copyManifest($sourceManifestFile, $androidManifestFile);
mergeManifests($mergeManifests, $androidManifestFile);
generateR($genDirectory);
--EXPECT--
