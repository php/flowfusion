--TEST--
Translated seed (inferredbugs-java: FOX/1)
--FILE--
<?php

$in = "University of Leipzig in Leipzig..";

$query = "select * from search.termextract where context=";

$url = "http://query.yahooapis.com/v1/public/yql?diagnostics=false&q=" . urlencode($query . "'" . $in . "'");

$context = stream_context_create(array(
    'http' => array(
        'method' => 'GET',
    ),
));

$response = file_get_contents($url, false, $context);

if ($response !== false) {
    echo $response;
}

?>
--EXPECT--
