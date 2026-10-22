--TEST--
Translated seed (inferredbugs-csharp: MegaApiClient/1)
--FILE--
<?php
$values = [];
for ($i = 0; $i < 3; $i++) {
    $values[] = (string)$i;
}
$lazyClient = null;
$log = function ($x) {
    echo $x, PHP_EOL;
};
$initializeClient = function () use (&$lazyClient, $log) {
    if ($lazyClient === null) {
        $client = new stdClass();
        $client->events = [];
        $client->connect = function () {};
        $client->apiRequestFailed = function ($apiResult, $apiUrl, $attemptNum, $retryDelay, $responseJson, $exception) use ($log) {
            $log("ApiRequestFailed: $apiResult, $apiUrl, $attemptNum, $retryDelay, $responseJson, $exception");
        };
        $lazyClient = $client;
        $log("Client created");
    }
    return $lazyClient;
};
$client = $initializeClient();
$client2 = $initializeClient();
$protectedNodes = [];
$permanentNodes = [];
foreach ($values as $v) {
    $protectedNodes[] = $v;
    $permanentNodes[] = strrev($v);
}
$log(implode(",", $protectedNodes));
$log(implode(",", $permanentNodes));
$client->apiRequestFailed->__invoke("Error", "http://example.invalid", 1, 0, "{}", null);
--EXPECT--
