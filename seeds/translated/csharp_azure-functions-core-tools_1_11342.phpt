--TEST--
Translated seed (inferredbugs-csharp: azure-functions-core-tools/1)
--FILE--
<?php
$body = "alpha";
$headers = ["X-Test" => ["a", "b"], "Content-Type" => ["text/plain"]];
$request = [
    "method" => "POST",
    "uri" => "http://example.com/path?x=1",
    "version" => "1.1",
    "content" => [
        "data" => $body,
        "headers" => $headers
    ],
    "properties" => ["p1" => 123, "p2" => "v"],
    "headers" => ["Accept" => ["*/*"], "X-Flag" => ["1"]]
];

$clone = [
    "method" => $request["method"],
    "uri" => $request["uri"],
    "version" => $request["version"],
    "content" => [
        "data" => (string)$request["content"]["data"],
        "headers" => $request["content"]["headers"]
    ],
    "properties" => $request["properties"],
    "headers" => $request["headers"]
];

$clone["content"]["data"] .= "!";
$clone["headers"]["X-Flag"][] = "2";
$clone["properties"]["p3"] = true;

echo $request["content"]["data"], "\n";
echo implode(",", $request["headers"]["X-Flag"]), "\n";
echo count($request["properties"]), "\n";
--EXPECT--
