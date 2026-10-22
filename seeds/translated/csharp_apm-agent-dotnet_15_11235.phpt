--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/15)
--FILE--
<?php
$lines = [];
$processing = [];
$spans = [];
$transaction = ['spans' => &$spans];

$req = ['method' => 'GET', 'url' => 'https://elastic.co'];
$key = 'System.Net.Http.HttpRequestOut.Start';
if ($key === 'System.Net.Http.HttpRequestOut.Start' && is_array($req)) {
    $processing[spl_object_id((object)$req)] = $req;
}

$reqId = spl_object_id((object)$req);
if (isset($processing[$reqId])) {
    $span = ['context' => ['http' => ['url' => $processing[$reqId]['url'], 'method' => $processing[$reqId]['method']]], 'type' => 'external', 'subtype' => 'http', 'action' => null, 'name' => 'GET localhost', 'duration' => 1, 'id' => 1];
    $spans[] = $span;
    unset($processing[$reqId]);
}

if (!empty($spans) && $spans[0]['context']['http']['url'] === 'https://elastic.co' && $spans[0]['context']['http']['method'] === 'GET') {
    echo "";
}
?>
--EXPECT--
