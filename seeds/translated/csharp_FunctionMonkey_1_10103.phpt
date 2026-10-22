--TEST--
Translated seed (inferredbugs-csharp: FunctionMonkey/1)
--FILE--
<?php
$body = file_get_contents('php://stdin');
$data = json_decode($body, true);
parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
$name = $query['name'] ?? ($data['name'] ?? null);
echo $name !== null ? "Hello, $name" : "Please pass a name on the query string or in the request body";
?>
--EXPECT--
