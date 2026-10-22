--TEST--
Translated seed (inferredbugs-java: Resty/2)
--FILE--
<?php

$url = "http://ws.geonames.org/postalCodeLookupJSON?postalcode=66780&country=DE";
$json = file_get_contents($url);
$data = json_decode($json, true);
$name = $data['postalcodes'][0]['placeName'];

echo $name;
--EXPECT--
