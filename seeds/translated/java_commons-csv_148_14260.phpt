--TEST--
Translated seed (inferredbugs-java: commons-csv/148)
--FILE--
<?php
$csvFile = fopen('optd_por_public.csv', 'r');
$header = fgetcsv($csvFile, 0, '^');
$locationTypeIndex = array_search('location_type', $header);
while (($record = fgetcsv($csvFile, 0, '^')) !== false) {
    $locationType = $record[$locationTypeIndex] ?? null;
}
fclose($csvFile);
--EXPECT--
