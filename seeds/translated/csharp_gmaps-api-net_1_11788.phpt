--TEST--
Translated seed (inferredbugs-csharp: gmaps-api-net/1)
--FILE--
<?php
$address = "1600 Pennsylvania Ave NW, Washington, DC 20500";
$data = [
    "status" => "OK",
    "results" => [
        [
            "formatted_address" => $address . ", USA",
            "geometry" => [
                "location" => [
                    "lat" => 38.8976633,
                    "lng" => -77.0365739,
                ],
            ],
        ],
    ],
];

if ($data["status"] === "OK" && count($data["results"]) > 0) {
    $result = $data["results"][0];
    echo "Full Address: " . $result["formatted_address"] . PHP_EOL;
    echo "Latitude: " . $result["geometry"]["location"]["lat"] . PHP_EOL;
    echo "Longitude: " . $result["geometry"]["location"]["lng"] . PHP_EOL;
    echo PHP_EOL;
} else {
    echo "Unable to geocode.  Status={$data["status"]} and ErrorMessage=" . ($data["error_message"] ?? "") . PHP_EOL;
}
?>
--EXPECT--
