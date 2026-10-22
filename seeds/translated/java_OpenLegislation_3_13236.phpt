--TEST--
Translated seed (inferredbugs-java: OpenLegislation/3)
--FILE--
<?php

$districts = [];
$districtJsonFolderPath = 'data/districts/';

for ($i = 1; $i <= 62; $i++) {
    $jsonPath = $districtJsonFolderPath . 'sd' . $i . '.json';
    $jsonData = file_get_contents($jsonPath);
    $jsono = json_decode($jsonData, true);
    
    $senatorName = $jsono['senator']['name'];
    $senatorName = str_replace("�", "e", $senatorName);
    $jsono['senator']['name'] = $senatorName;
    
    $senatorKey = basename($jsono['senator']['url']);
    $senatorKey = str_replace(['-jr', '-sr'], '', $senatorKey);
    $senatorKeyParts = explode('-', $senatorKey);

    if (strpos($senatorName, '-') !== false) {
        $senatorKey = $senatorKeyParts[count($senatorKeyParts) - 2] . '-' . $senatorKeyParts[count($senatorKeyParts) - 1];
    } else {
        $senatorKey = end($senatorKeyParts);
    }
    
    $jsono['senator']['key'] = $senatorKey;
    $districts[] = $jsono;
}

usort($districts, function($districtA, $districtB) {
    return strcmp($districtA['senator']['key'], $districtB['senator']['key']);
});
--EXPECT--
