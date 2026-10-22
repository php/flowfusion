--TEST--
Translated seed (inferredbugs-java: MicroCommunity/6)
--FILE--
<?php

$floors = [];
$owners = [];
$rooms = [];
$parkingSpaces = [];

$workbook = []; // Simulating the workbook for demonstration

// Simulating data extraction and population of lists
function getFloors(&$floors) {
    $floors[] = ["floorNum" => "1", "unitNum" => "A", "layerCount" => "10", "lift" => "有"];
}

function getOwners(&$owners) {
    $owners[] = ["ownerNum" => "001", "ownerName" => "张三", "sex" => "0", "age" => 30, "tel" => "123456789"];
}

function getRooms(&$rooms, $floors, $owners) {
    $rooms[] = ["roomNum" => "101", "floor" => $floors[0], "layer" => 1, "section" => "1", "builtUpArea" => 80.0, "importOwner" => $owners[0]];
}

function getParkingSpaces(&$parkingSpaces, $owners) {
    $parkingSpaces[] = ["psNum" => "P1", "typeCd" => "001", "area" => 20.0, "importOwner" => $owners[0]];
}

function importExcelDataValidate($floors, $owners, $rooms, $parkingSpaces) {
    // Validation logic can be added here
}

function dealExcelData($floors, $owners, $rooms, $parkingSpaces) {
    return "Data processed successfully";
}

getFloors($floors);
getOwners($owners);
getRooms($rooms, $floors, $owners);
getParkingSpaces($parkingSpaces, $owners);
importExcelDataValidate($floors, $owners, $rooms, $parkingSpaces);
$result = dealExcelData($floors, $owners, $rooms, $parkingSpaces);

echo $result;
--EXPECT--
