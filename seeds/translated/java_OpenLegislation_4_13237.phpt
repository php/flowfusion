--TEST--
Translated seed (inferredbugs-java: OpenLegislation/4)
--FILE--
<?php

$districts = [];

for ($i = 1; $i <= 62; $i++) {
    $jsonPath = "data/districts/sd" . $i . ".json";
    $jsonb = file_get_contents($jsonPath);
    $jsono = json_decode($jsonb, true);

    $senatorName = $jsono['senator']['name'];
    $senatorName = str_replace("�", "e", $senatorName);
    $jsono['senator']['name'] = $senatorName;

    $senatorKey = basename($jsono['senator']['url']);
    $senatorKey = str_replace(["-jr", "-sr"], "", $senatorKey);
    $senatorKeyParts = explode("-", $senatorKey);

    if (strpos($senatorName, "-") !== false) {
        $senatorKey = $senatorKeyParts[count($senatorKeyParts) - 2] . '-' . end($senatorKeyParts);
    } else {
        $senatorKey = end($senatorKeyParts);
    }

    $jsono['senator']['key'] = $senatorKey;
    $districts[] = $jsono;
}

usort($districts, function ($districtA, $districtB) {
    return strcmp($districtA['senator']['key'], $districtB['senator']['key']);
});

header('Content-Type: application/json');
echo json_encode($districts);
--EXPECT--
