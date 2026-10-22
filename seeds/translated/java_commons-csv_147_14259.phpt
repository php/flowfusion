--TEST--
Translated seed (inferredbugs-java: commons-csv/147)
--FILE--
<?php
$csvData = "src/test/resources/csv-167/sample1.csv";
$handle = fopen($csvData, "r");
$totcomment = 0;
$totrecs = 0;
$lastWasComment = false;

while (($s = fgets($handle)) !== false) {
    if (strpos($s, '#') === 0) {
        if (!$lastWasComment) {
            $totcomment++;
        }
        $lastWasComment = true;
    } else {
        $totrecs++;
        $lastWasComment = false;
    }
}
fclose($handle);

$rows = array_map('str_getcsv', file($csvData));
$comments = 0;
$records = 0;

foreach ($rows as $row) {
    if (isset($row[0]) && strpos($row[0], '#') === 0) {
        $comments++;
    } else {
        $records++;
    }
}

// The values of $totcomment and $comments should match, and the same for $totrecs and $records.
$totcomment;
$comments;
$totrecs;
$records;
?>
--EXPECT--
