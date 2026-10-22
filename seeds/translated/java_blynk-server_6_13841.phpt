--TEST--
Translated seed (inferredbugs-java: blynk-server/6)
--FILE--
<?php

$staticResourcesFolder = 'resources/';
$staticResources = [];

$jarPath = __FILE__; // Simulating getting a jar path
$zip = new ZipArchive;

if ($zip->open($jarPath) === true) {
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $entryName = $zip->getNameIndex($i);
        if (strpos($entryName, $staticResourcesFolder) === 0 &&
            (substr($entryName, -3) === 'js' ||
             substr($entryName, -4) === 'css' ||
             substr($entryName, -5) === 'html' ||
             substr($entryName, -4) === 'ico' ||
             substr($entryName, -4) === 'png')) {
            $staticResources[] = $entryName;
        }
    }
    $zip->close();
}

foreach ($staticResources as $resource) {
    echo $resource . "\n";
}
--EXPECT--
