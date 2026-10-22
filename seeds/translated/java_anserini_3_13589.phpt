--TEST--
Translated seed (inferredbugs-java: anserini/3)
--FILE--
<?php

if ($argc < 4 || $argc > 5) {
    fwrite(STDERR, "Usage: php script.php <topicsFile> <qrelsFile> <submissionFile> <indexDir> [querySpec]\n");
    exit(1);
}

$topicsFile = $argv[1];
$qrelsFile = $argv[2];
$submissionFile = $argv[3];
$indexDir = $argv[4] ?? "T";

$maxResults = 1000;

$fieldSet = [];
if (strpos($indexDir, 'T') !== false) $fieldSet[] = "title";
if (strpos($indexDir, 'D') !== false) $fieldSet[] = "description";
if (strpos($indexDir, 'N') !== false) $fieldSet[] = "narrative";

$docIdField = "docid";
$out = fopen('php://stdout', 'w');

$qqs = file($topicsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($qqs as $line) {
    $title = trim($line);
    $query = "SELECT * FROM documents WHERE body LIKE '%" . addslashes($title) . "%' LIMIT $maxResults"; // Simulating query execution
    
    $results = range(1, $maxResults); // Simulating search results
    foreach ($results as $i => $doc) {
        fwrite($out, sprintf("%s Q0 %s %d %f Lucene\n", $title, $docIdField, $i + 1, rand(0, 100) / 100));
    }
}

fclose($out);
--EXPECT--
