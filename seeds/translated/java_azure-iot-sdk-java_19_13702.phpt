--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/19)
--FILE--
<?php
$MAX_DEVICES = 100;
$PAGE_SIZE = 50;
$PROPERTY_KEY_QUERY = "key_";
$PROPERTY_VALUE_QUERY = "value_";
$DESIRED_PROPERTIES_PROPAGATION_TIME_MILLIS = 1000;

function addMultipleDevices($maxDevices) {
    return range(0, $maxDevices - 1);
}

function setDesiredProperties($devices, $queryProperty, $queryPropertyValue) {
    foreach ($devices as $device) {
        $device['desiredProperties'][$queryProperty] = $queryPropertyValue;
    }
}

function queryDevices($devices, $queryProperty) {
    $count = 0;
    foreach ($devices as $device) {
        if (isset($device['desiredProperties'][$queryProperty])) {
            $count++;
        }
    }
    return $count;
}

$devicesUnderTest = addMultipleDevices($MAX_DEVICES);
$queryProperty = $PROPERTY_KEY_QUERY . uniqid();
$queryPropertyValue = $PROPERTY_VALUE_QUERY . uniqid();

setDesiredProperties($devicesUnderTest, $queryProperty, $queryPropertyValue);
usleep($DESIRED_PROPERTIES_PROPAGATION_TIME_MILLIS * 1000);

$actualNumOfDevices = queryDevices($devicesUnderTest, $queryProperty);
$expectedNumOfDevices = $MAX_DEVICES;

if ($actualNumOfDevices !== $expectedNumOfDevices) {
    echo "Expected $expectedNumOfDevices devices, found $actualNumOfDevices.";
}

function queryCollectionCanReturnEmptyQueryResults($devices) {
    $queryProperty = "nonexistentProperty";
    $count = 0;
    foreach ($devices as $device) {
        if (isset($device['desiredProperties'][$queryProperty])) {
            $count++;
        }
    }
    return $count === 0;
}

if (!queryCollectionCanReturnEmptyQueryResults($devicesUnderTest)) {
    echo "Expected empty result for nonexistent property.";
}
--EXPECT--
