--TEST--
Translated seed (inferredbugs-java: anserini/1)
--FILE--
<?php

if ($argc < 5 || $argc > 6) {
    fwrite(STDERR, "Usage: php script.php <topicsFile> <qrelsFile> <submissionFile> <indexDir> [querySpec]\n");
    exit(1);
}

$topicsFile = $argv[1];
$qrelsFile = $argv[2];
$submissionFile = $argv[3];
$indexDir = $argv[4];
$fieldSpec = $argc == 6 ? $argv[5] : "T";

$maxResults = 1000;
$fieldSet = [];
if (strpos($fieldSpec, 'T') !== false) $fieldSet[] = "title";
if (strpos($fieldSpec, 'D') !== false) $fieldSet[] = "description";
if (strpos($fieldSpec, 'N') !== false) $fieldSet[] = "narrative";

$qqs = file($topicsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$out = fopen('php://stdout', 'w');

foreach ($qqs as $line) {
    list($qid, $title) = explode("\t", $line);
    $query = strtolower(trim($title));
    
    // Simulate search result
    $results = [];
    for ($i = 0; $i < $maxResults; $i++) {
        $results[] = [
            'docid' => "doc" . ($i + 1),
            'score' => rand(1, 100) / 100
        ];
    }
    
    foreach ($results as $i => $doc) {
        fprintf($out, "%s Q0 %s %d %f Lucene\n", $qid, $doc['docid'], $i + 1, $doc['score']);
    }
}

fclose($out);
--EXPECT--
