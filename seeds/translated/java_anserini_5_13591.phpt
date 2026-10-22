--TEST--
Translated seed (inferredbugs-java: anserini/5)
--FILE--
<?php

if ($argc != 2) {
    fwrite(STDERR, "Usage: php SearchTimeUtil.php <indexDir>\n");
    exit(1);
}

$topics = ["topics.web.1-50.txt", "topics.web.51-100.txt", "topics.web.101-150.txt", "topics.web.151-200.txt", "topics.web.201-250.txt", "topics.web.251-300.txt"];

$searcher = new SearchClueWeb09b($argv[1]);

foreach ($topics as $topicFile) {
    $topicsFile = "src/resources/topics-and-qrels/$topicFile";
    $queries = SearchClueWeb09b::readWebTrackQueries($topicsFile);
    for ($i = 1; $i <= 3; $i++) {
        $start = microtime(true);
        $submissionFile = tempnam(sys_get_temp_dir(), "$topicFile_$i") . '.tmp';
        $searcher->search($queries, $submissionFile, new BM25Similarity(0.9, 0.4), 1000);
        $durationMillis = (microtime(true) - $start) * 1000;
        echo "$topicFile_$i search completed in " . sprintf("%02d:%02d:%03d", ($durationMillis / 60000), ($durationMillis % 60000) / 1000, $durationMillis % 1000) . "\n";
    }
}

$searcher->close();

?>
--EXPECT--
