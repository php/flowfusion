--TEST--
Translated seed (inferredbugs-java: anserini/2)
--FILE--
<?php

if ($argc < 4 || $argc > 5) {
    fwrite(STDERR, "Usage: php script.php <topicsFile> <qrelsFile> <submissionFile> <indexDir> [querySpec]\n");
    exit(1);
}

$topicsFile = $argv[1];
$qrelsFile = $argv[2];
$submissionFile = $argv[3];
$indexDir = $argv[4];
$fieldSpec = $argc == 5 ? $argv[4] : "T";

$maxResults = 1000;
$fieldSet = [];

if (strpos($fieldSpec, 'T') !== false) $fieldSet[] = "title";
if (strpos($fieldSpec, 'D') !== false) $fieldSet[] = "description";
if (strpos($fieldSpec, 'N') !== false) $fieldSet[] = "narrative";

$queries = file($topicsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$output = fopen($submissionFile, 'w');

foreach ($queries as $line) {
    $title = explode(' ', $line)[1]; // Simulating getting the title from the line
    $query = buildBagOfWordsQuery("body", $title);
    $results = search($query, $maxResults); // Simulating a search function

    foreach ($results as $i => $doc) {
        $qid = explode(' ', $line)[0]; // Simulating getting query ID from the line
        fwrite($output, sprintf("%s Q0 %s %d %f %s\n", $qid, $doc['docid'], $i + 1, $doc['score'], "Lucene"));
    }
}

fclose($output);

function buildBagOfWordsQuery($field, $title) {
    // Simulating the logic of building a query; returns a "query" string representation
    return "$field:$title"; 
}

function search($query, $maxResults) {
    // Simulating search results returned from a search
    return array_fill(0, $maxResults, ['docid' => uniqid(), 'score' => rand(1, 100) / 100]);
}
--EXPECT--
