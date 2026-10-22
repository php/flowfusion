--TEST--
Translated seed (inferredbugs-java: android-maven-plugin/8)
--FILE--
<?php

$manifestVersionCode = 123;
$manifestVersionCodeAutoIncrement = false;
$manifestVersionCodeUpdateFromVersion = false;
$projectVersion = '3.2.1';
$manifestVersionName = null;

function updateVersionCode($currentVersionCode, $autoIncrement, $updateFromVersion, $versionString) {
    if ($autoIncrement) {
        return $currentVersionCode + 1;
    } elseif ($updateFromVersion) {
        $versionParts = explode('.', $versionString);
        return $versionParts[0] * 1000000 + $versionParts[1] * 1000 + $versionParts[2];
    }
    return $currentVersionCode;
}

$currentVersionCode = $manifestVersionCode;
$currentVersionCode = updateVersionCode($currentVersionCode, $manifestVersionCodeAutoIncrement, $manifestVersionCodeUpdateFromVersion, $projectVersion);

if ($manifestVersionName === null) {
    $manifestVersionName = $projectVersion;
}

echo "Setting android:versionCode to " . $currentVersionCode . PHP_EOL;
echo "Setting android:versionName to " . $manifestVersionName . PHP_EOL;
--EXPECT--
